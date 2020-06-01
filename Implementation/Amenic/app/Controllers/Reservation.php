<?php namespace App\Controllers;

/*
    Author: Miloš Živkovic
    Github: zivkovicmilos
*/

use App\Models\UserModel;
use App\Entities\Reservation as EntitiesReservation;
use App\Entities\Seat;
use App\Models\CinemaModel;
use App\Models\MovieModel;
use App\Models\ProjectionModel;
use App\Models\ReservationModel;
use App\Models\RoomModel;
use App\Models\SeatModel;
use App\Models\TechnologyModel;
use Exception;

use function App\Helpers\isAuthenticated;
use function App\Helpers\isValid;
use function App\Helpers\sendMail;
use function App\Helpers\sendReservationInfo;

/** Reservation – Controller that controls ticket reservations
 * 
 * @version 1.0
 */

class Reservation extends BaseController {

    /**
     * Serves the room layout for a specific projection
     * @param Number idPro
     * @return view
     */

    public function index($idPro) {

        $token = $this->getToken();
        if (is_null($token)) {
            header('Location: /');
            exit();
        }

        $movieModel = new MovieModel();
        $projectionModel = new ProjectionModel();
        $cinemaModel = new CinemaModel();

        $projection = $projectionModel->find($idPro);
        
        if(is_null($projection)) {
            // Bad screening request
            header('Location: /');
            exit();
        }

        $cinemaName = $cinemaModel->find($projection->email)->name;
        $roomName = $projection->roomName;

        $roomModel = new RoomModel();
        $room = $roomModel
                        ->where('name', $roomName)
                        ->where('email', $projection->email)
                        ->findAll();
        
        $numRows = $room[0]->numberOfRows;
        $numCols = $room[0]->seatsInRow;
        $movie = $movieModel->find($projection->tmdbID);

        return view('Reservations/reservation', 
        ['movie' => $movie, 
        'idPro' => $idPro, 
        'price' => $projection->price, 
        'cinemaName' => $cinemaName, 
        'roomName' => $roomName,
        'ticketPrice' => $projection->price,
        'numRows' => $numRows,
        'numCols' => $numCols
        ]);
    }

    /**
     * Gets all of the reservations for a specific projection
     * @return JSON
     */

    public function getReservations() {

        $token = $this->getToken();
        if (is_null($token)) {
            header('Location: /');
            exit();
        }

        $idPro = $_REQUEST['idPro'];

        $seatModel = new SeatModel();

        $results = $seatModel
                    ->where('idPro', $idPro)
                    ->where('status !=', 'free')
                    ->findAll();

        echo json_encode($results);
    }

    /** 
     * Gets the cookie containing basic info of a logged in user
     * @return object
     */

    private function getToken() {
        helper('auth');

        if (isset($_COOKIE['token'])) {
            $tokenCookie = $_COOKIE['token'];   
            $token = isValid($tokenCookie);
            
            if ($token && isAuthenticated("RUser"))
            {
                $image = (new UserModel())->find($token->email);
                $image = $image->image;
        
                $token->image = $image;
                return $token;
            }

            return $token;
        }
        return null;
    }

    /** 
     * Confirms the reservation in the database, or returns an error message
     * @return JSON
     */

    public function confirm() {

        $token = $this->getToken();
        if (is_null($token)) {
            header('Location: /');
            exit();
        }

        $idPro = $_POST['idPro'];
        $token = $this->getToken();
        
        if(is_null($token)) {
            // Bad token
            echo json_encode("BAD");
        }

        $db = db_connect();

        $message = "OK";

        $reservationModel = new ReservationModel();
        $seatModel = new SeatModel();
        
        
        $numReservations = $reservationModel
                            ->join('Seats', 'Reservations.idRes = Seats.idRes')
                            ->where('email', $token->email)
                            ->where('Reservations.idPro', $idPro)
                            
                            ->findAll();

        if(is_null($numReservations) || count($numReservations) > 5) {
            $message = "BAD";
        } else {
            $reservation = new EntitiesReservation([
                'confirmed' => 0,
                'idPro' => $idPro,
                'email' => $token->email
            ]);

            $db->transStart();

            $reservationModel->insert($reservation);

            $last = $reservationModel->where('email', $token->email)->findAll();
            // Grab the just added idRes
            $idRes = $last[count($last)-1]->idRes;

            $pairs = explode(' ', $_POST['seats']);

            $reservedSeats = [];

            $seat = null;
            foreach($pairs as $pair) {
                preg_match('/([0-9]{1,2}):([0-9]{1,2})/', $pair, $resArr);

                // Check if seats are still free
                $foundSeat = $seatModel
                                    ->where('idPro', $idPro)
                                    ->where('rowNumber', $resArr[1])
                                    ->where('seatNumber', $resArr[2])
                                    ->findAll();
                
                if($foundSeat[0]->status != 'free') {
                    $db->transRollback();
                    echo json_encode('TAKEN');
                    return;
                }

                $seatModel->where([
                    'idPro' => $idPro, 
                    'rowNumber' => $resArr[1], 
                    'seatNumber' => $resArr[2]
                    ])->set([
                    'status' => 'reserved',
                    'idRes' => $idRes            
                    ])->update();

                $db->transCommit();

                $roomLetter = $this->rowNumToStr($resArr[1]);
                array_push($reservedSeats, "$roomLetter$resArr[2]");
            }

            $projectionModel = new ProjectionModel();
            $res = $projectionModel->find($idPro);

            $movieModel = new MovieModel();
            $movie = $movieModel->find($res->tmdbID);

            $cinemaModel = new CinemaModel();
            $cinema = $cinemaModel->find($res->email);

            $technologyModel = new TechnologyModel();
            $tech = $technologyModel->find($res->idTech);

            $hour = intval($movie->runtime / 60);
            $minutes = intval($movie->runtime % 60);
            $year = substr($movie->released, 0, 4);
            

            helper('mailer_helper');

            $resSeatsStr = '';
            foreach($reservedSeats as $el) {
                $resSeatsStr .= "$el ";
            }


            $last = $reservationModel->where('email', $token->email)->findAll();
            $lastReservation = $last[count($last)-1];

            $ret = sendReservationInfo($lastReservation, $resSeatsStr, $res, $movie, $cinema, $tech, false);
        }

        echo json_encode($message);
    }

    /** 
     * Helper function that converts a row number to a row letter
     * @return Character
     */

    private function rowNumToStr($rowNumber) {
        return chr($rowNumber + 65 - 1);
    }

}