<?php namespace App\Controllers;

/*
    Author: Miloš Živkovic
    Github: zivkovicmilos
*/

use App\Entities\Reservation as EntitiesReservation;
use App\Entities\Seat;
use App\Models\CinemaModel;
use App\Models\MovieModel;
use App\Models\ProjectionModel;
use App\Models\ReservationModel;
use App\Models\SeatModel;
use App\Models\TechnologyModel;

use function App\Helpers\isAuthenticated;
use function App\Helpers\isValid;
use function App\Helpers\sendMail;

class Reservation extends BaseController {

    public function index() {

        $movieModel = new MovieModel();
        $projectionModel = new ProjectionModel();
        $cinemaModel = new CinemaModel();

        $projection = $projectionModel->find(7);

        $cinemaName = $cinemaModel->find('milos@cineplexx.com')->name;
        $roomName = 'Merlyn Monroe';
        
        // TEST AREA //
        // END OF TEST //
        
        $movie = $movieModel->find(437068);
        return view('Reservations/reservation', ['movie' => $movie, 'idPro' => 7, 'price' => $projection->price, 'cinemaName' => $cinemaName, 'roomName' => $roomName]);
    }

    public function getReservations() {
        $idPro = $_REQUEST['idPro'];

        $seatModel = new SeatModel();

        $results = $seatModel
                    ->where('idPro', $idPro)
                    ->where('status', 'reserved')
                    ->orWhere('status', 'sold')
                    ->findAll();

        echo json_encode($results);
    }

    private function getToken() {
        helper('auth');

        if (isset($_COOKIE['token'])) {
            $tokenCookie = $_COOKIE['token'];   
            $token = isValid($tokenCookie);
            
            return $token;
        }
        return null;
    }

    public function confirm() {

        $idPro = $_POST['idPro'];
        $token = $this->getToken();
        $message = "OK";

        $reservationModel = new ReservationModel();
        $seatModel = new SeatModel();
        
        $numReservations = $reservationModel
                                ->where('email', 'rUser2')
                                ->join('Seats', 'Reservations.idRes = Seats.idRes', 'Reservations.idPro = Seats.idPro')
                                ->findAll();

        if(count($numReservations) > 5) {
            $message = "BAD";
        } else {
            $reservation = new EntitiesReservation([
                'confirmed' => 0,
                'idPro' => $idPro,
                'email' => 'rUser2' // todo change to $token->email
            ]);

            $reservationModel->insert($reservation);

            $last = $reservationModel->where('email', 'rUser2')->findAll();
            // Grab the just added idRes
            $idRes = $last[count($last)-1]->idRes;

            $pairs = explode(' ', $_POST['seats']);

            $reservedSeats = [];

            $seat = null;
            foreach($pairs as $pair) {
                preg_match('/([0-9]{1,2}):([0-9]{1,2})/', $pair, $resArr);
    
                $seat = new Seat([
                    'idPro' => $idPro,
                    'rowNumber' => $resArr[1],
                    'seatNumber' => $resArr[2],
                    'status' => 'reserved',
                    'idRes' => $idRes
                ]);
                $seatModel->insert($seat);
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

            $content = "Dear user, <br />
                        Thank you for using the Amenic platform for your cinema booking needs. <br />
                        Your reservation has been logged, and you can see the details below: <br /><br />
                        $movie->title · $hour h $minutes m · $cinema->name, $res->roomName · $tech->name <br />
                        Reserved seats: <br />
                        $resSeatsStr <br />
            ";

            sendMail('milosmisazivkovic@gmail.com', "Reservation - $movie->title", $content);
        }

        echo json_encode($message);
    }

    private function rowNumToStr($rowNumber) {
        return chr($rowNumber + 65 - 1);
    }

}