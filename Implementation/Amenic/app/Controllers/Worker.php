<?php namespace App\Controllers;

/*
    Author: Miloš Živkovic
    Github: zivkovicmilos

    Author: Martin Mitrović
    Github: Rpsaman13000

*/

use \App\Models\UserModel;
use \App\Models\WorkerModel;
use App\Libraries\Upload;

use App\Entities\Seat;
use App\Models\CinemaModel;
use App\Models\MovieModel;
use App\Models\ProjectionModel;
use App\Models\ReservationModel;
use App\Models\RUserModel;
use App\Models\SeatModel;
use App\Models\TechnologyModel;
use CodeIgniter\I18n\Time;
use Exception;

use function App\Helpers\isAuthenticated;
use function App\Helpers\isValid;
use function App\Helpers\generateToken;
use function App\Helpers\setToken;
use function App\Helpers\sendMailOnReservationDelete;

/** Worker – Controller that handles Worker actions
 * 
 * @version 1.0
 */

class Worker extends BaseController
{

    /** 
     * Gets the cookie containing basic info of a logged in user
     * @return object
     */

    private function getToken()
    {
        helper('auth');

        if (isset($_COOKIE['token']))
        {
            $tokenCookie = $_COOKIE['token'];   
            $token = isValid($tokenCookie);
            
            if ($token && isAuthenticated("Worker"))
            {
                $image = (new UserModel())->find($token->email);
                $image = $image->image;
        
                $token->image = $image;
        
                return $token;
            }
        }
        return null;
    }

    /** Creates a form to allow admin to change his information
     * @return view SettingsView using data - [$adminInfo, $curActiveMenu, $adminImage, $accountType, $accountToken, $errors] 
     */

    public function settings()
    {
        $token = $this->getToken();
        if (is_null($token))
            return view('AdminBreachMessage',[]);

        $data = [
            'firstName' => $token->firstName,
            'lastName' => $token->lastName,
            'email' =>  $token->email,
            ];

        //you have to save admin info twice beacause this page is being used by all users
        return view('SettingsView',['data' => $data, 'actMenu' => 5, 'image' => $token->image, 'userType' => 'Worker', 'token' => $token, 'errors' => '' ]);    
    }

    /** Saves changes to admin account
     * @return callable function to show desired menu
     */
    public function saveSettings()
    {
        $token = $this->getToken();
        if (is_null($token))
            return view('AdminBreachMessage',[]);        

        $validation =  \Config\Services::validation();
        $db = db_connect();

        //fetching data
        $fName = $_POST['fName'];
        $lName = $_POST['lName'];
        $email = $token->email;
        $pswdOld = $_POST['pswdOld'];
        $pswdNew = $_POST['pswdNew'];
        $image = $this->request->getFile('profilePicture');
        
        $form = [
            'fName' => $fName,
            'lName' => $lName,
            'email' => $email,
            'pswd' => [ 
                'oldPswd' => $pswdOld,
                'newPswd' => $pswdNew,
                'email' => $email
            ],
            'profilePicture' => $image
        ]; 
            
        $valid = $validation->run($form, "adminSettingsCheck");
    
        if($valid != 1)
        {
            
            $data = [
                'firstName' => $token->firstName,
                'lastName' => $token->lastName,
                'email' =>  $token->email,
                ];

            $errors = $validation->getErrors();

            return view('SettingsView',['data' => $data, 'actMenu' => 5, 'image' => $token->image, 'userType' => 'Worker', 'token' => $token, 'errors' => $errors ]);    
        }

        //password remains the same
        if(strcmp($pswdNew,"") == 0)
        {
            $pswdNew = (new UserModel())->find($email)->password;
        }
        else
        {
            $pswdNew = password_hash($pswdNew,PASSWORD_BCRYPT, ['cost' => 8]);
        }
        
        try
        {
            //update database
            $db->transBegin();
            $img=null;
            if (strcmp($image->getName(),"") !=0)
            {
                $img = base64_encode(file_get_contents($image));
                (new UserModel())->where(['email' => $email])->set([
                    'image' => $img,             
                    ])->update();            
            }

            (new UserModel())->where(['email' => $email])->set([
                'password' => $pswdNew               
                ])->update();
            (new WorkerModel())->where(['email' => $email])->set(['firstName' => $fName, 'lastName' => $lName])->update();
            $db->transCommit();
        }
        catch (Exception $e)
        {
            $db->transRollback();
            throw new Exception("Transaction ".get_class($this).".saveChanges(".$email.") failed!<br/>".$e->getMessage());
        }

        //change token
        $payload = [
            "firstName" => $fName,
            "lastName" => $lName,
            "email" => $email,
            "type" => "Worker"
        ]; 
        setToken(generateToken($payload));
        $token = $this->getToken();

        return redirect()->to('/HomeController');
    }

    /** 
     * Renders the reservations page of the worker
     * @return view
     */

    public function index() {

        $token = $this->getToken();
        if (is_null($token)) {
            header('Location: /');
            exit();
        }

        $cinemaModel = new CinemaModel();
        $cinema = $cinemaModel->find($token->cinemaEmail);

        return view('Worker/worker.php', ['cinema' => $cinema, 'actMenu' => 0, 'userImage' => $token->image, "userFullName" => $token->firstName]);
    }

    // RESERVATION LIST //

    /** 
     * Fetches all of the reservations for a specific cinema
     * @return JSON
     */

    public function getReservations() {
        $cinemaEmail = $_REQUEST['cinemaEmail'];

        $phrase = $_REQUEST['phrase'];

        $projectionsModel = new ProjectionModel();

        $projections = $projectionsModel
                            ->where('Projections.email', $cinemaEmail)
                            ->where('Projections.canceled', 0)
                            ->join('Reservations', 'Projections.idPro = Reservations.idPro')
                            ->join('RUsers', 'Reservations.email = RUsers.email')
                            ->join('Users', 'Reservations.email = Users.email')
                            ->like('RUsers.email',$phrase, $insensitiveSearch = TRUE)
                            ->orWhere('Projections.email', $cinemaEmail)
                            ->where('Projections.canceled', 0)
                            ->like('firstName',$phrase, $insensitiveSearch = TRUE)
                            ->orWhere('Projections.email', $cinemaEmail)
                            ->where('Projections.canceled', 0)
                            ->like('lastName',$phrase, $insensitiveSearch = TRUE)
                            ->orWhere('Projections.email', $cinemaEmail)
                            ->where('Projections.canceled', 0)
                            ->like('Reservations.idRes',$phrase, $insensitiveSearch = TRUE)
                            ->findAll();
                            
        echo json_encode($projections);
    }

    /** 
     * Fetches all of the seats for a specific reservation
     * @return JSON
     */

    public function getSeats() {
        $idRes = $_REQUEST['idRes'];

        $seatModel = new SeatModel();

        $seats = $seatModel
                    ->where('idRes', $idRes)
                    ->where('status', 'reserved')
                    ->findAll();
                            
        echo json_encode($seats);
    }

    /** 
     * Fetches the user
     * @return JSON
     */

    public function getUser() {
        $userEmail = $_REQUEST['userEmail'];

        $ruserModel = new RUserModel();

        $ruser = $ruserModel->find($userEmail);
                            
        echo json_encode($ruser);
    }

    /** 
     * Fetches projection details
     * @return JSON
     */

    public function getProjection() {
        $idPro= $_REQUEST['idPro'];

        $projectionsModel = new ProjectionModel();

        $projection = $projectionsModel->find($idPro);
                            
        echo json_encode($projection);
    }

    /** 
     * Fetches a specific movie
     * @return JSON
     */

    public function getMovie() {
        $tmdbID= $_REQUEST['tmdbID'];

        $movieModel = new MovieModel();

        $movie = $movieModel->find($tmdbID);
                            
        echo json_encode($movie);
    }

    /** 
     * Fetches the name of a specific technology
     * @return JSON
     */

    public function getTechnology() {
        $idTech= $_REQUEST['idTech'];

        $technologyModel = new TechnologyModel();

        $technology = $technologyModel->find($idTech);
                            
        echo json_encode($technology);
    }

    /** 
     * Confirms a user's reservation
     * @return JSON
     */

    public function confirmReservation() {
        $idRes = $_REQUEST['idRes'];

        $reservationModel = new ReservationModel();
        $reservation = $reservationModel->find($idRes);
        $reservation->confirmed = 1;

        $reservationModel->where([
            'idRes' => $idRes
            ])->set([
            'confirmed' => 1         
            ])->update();

        echo json_encode("ok");
    }

    /** 
     * Deletes a user's reservation
     * @return JSON
     */

    public function deleteReservation() {
        $idRes = $_POST['idRes'];

        $seatModel = new SeatModel();
        $seatModel->where('idRes', $idRes)->delete();

        $reservationModel = new ReservationModel();

        // email the reservation holder
        $reservation = $reservationModel->find($idRes);
        if (!$reservation->confirmed)
        {
            helper('mailer_helper');
            sendMailOnReservationDelete($reservation);
        }

        $reservationModel->where('idRes', $idRes)->delete();
        
        echo json_encode('ok');
    }

    // RESERVATION MODAL //

    /** 
     * Fetches all of the dates a specific movie is showing on
     * @return JSON
     */

    public function getDates() {
        $tmdbID =       $_REQUEST['tmdbID'];
        $cinemaEmail =  $_REQUEST['cinemaEmail'];

        $projectionsModel = new ProjectionModel();

        $dates = $projectionsModel
                            ->where('tmdbID', $tmdbID)
                            ->where('email', $cinemaEmail)
                            ->where('canceled', 0)
                            ->findAll();

        echo json_encode($dates);
    }

    /** 
     * Fetches all of the times a specific movie is showing at
     * @return JSON
     */

    public function getTimes() {
        $tmdbID =       $_REQUEST['tmdbID'];
        $cinemaEmail =  $_REQUEST['cinemaEmail'];

        $dateTimePattern = '/(.*)-(.*)-(.*)/';
        preg_match($dateTimePattern, $_REQUEST['date'], $dateMatches);

        $dateYear = (int)   $dateMatches[3];
        $dateMonth = (int)  $dateMatches[2];
        $dateDay =  (int)   $dateMatches[1];

        $date = Time::create($dateYear, $dateMonth, $dateDay, 0, 0, 0);
        $endOfDay= $date->addDays(1);

        $projectionsModel = new ProjectionModel();

        $times = $projectionsModel
                            ->where('tmdbID', $tmdbID)
                            ->where('dateTime >=', $date)
                            ->where('dateTime <', $endOfDay)
                            ->where('email', $cinemaEmail)
                            ->findAll();

        echo json_encode($times);
    }

    /** 
     * Fetches all of the rooms for a specific movie
     * @return JSON
     */

    public function getRooms() {
        $tmdbID =       $_REQUEST['tmdbID'];
        $cinemaEmail =  $_REQUEST['cinemaEmail'];
        $time =         $_REQUEST['time'];

        $timeArr = explode(':', $time);

        $dateTimePattern = '/(.*)-(.*)-(.*)/';
        preg_match($dateTimePattern, $_REQUEST['date'], $dateMatches);

        $dateYear = (int)   $dateMatches[3];
        $dateMonth = (int)  $dateMatches[2];
        $dateDay =  (int)   $dateMatches[1];


        $date = Time::create($dateYear, $dateMonth, $dateDay, $timeArr[0], $timeArr[1], $timeArr[2]);
        $endOfDay= $date->addDays(1);

        $projectionsModel = new ProjectionModel();

        $rooms = $projectionsModel
                            ->where('tmdbID', $tmdbID)
                            ->where('dateTime', $date)
                            ->where('email', $cinemaEmail)
                            ->findAll();

        echo json_encode($rooms);
    }

    /** 
     * Fetches all available technologies for a specific movie
     * @return JSON
     */

    public function getTech() {
        $tmdbID =       $_REQUEST['tmdbID'];
        $cinemaEmail =  $_REQUEST['cinemaEmail'];
        $roomName =     $_REQUEST['roomName'];
        $time =         $_REQUEST['time'];

        $timeArr = explode(':', $time);

        $dateTimePattern = '/(.*)-(.*)-(.*)/';
        preg_match($dateTimePattern, $_REQUEST['date'], $dateMatches);

        $dateYear = (int)   $dateMatches[3];
        $dateMonth = (int)  $dateMatches[2];
        $dateDay =  (int)   $dateMatches[1];

        $date = Time::create($dateYear, $dateMonth, $dateDay, $timeArr[0], $timeArr[1], $timeArr[2]);
        $endOfDay= $date->addDays(1);

        $projectionsModel = new ProjectionModel();

        $techs = $projectionsModel
                            ->where('tmdbID', $tmdbID)
                            ->where('dateTime', $date)
                            ->where('email', $cinemaEmail)
                            ->where('roomName', $roomName)
                            ->findAll();

        echo json_encode($techs);
    }

    /** 
     * Fetches projection details
     * @return JSON
     */

    public function getSpecProjection() {
        $idPro = $_REQUEST['idPro'];

        $projectionsModel = new ProjectionModel();

        $projection = $projectionsModel
                                    ->where('Projections.idPro', $idPro)
                                    ->join('Rooms', 'Projections.roomName = Rooms.name')
                                    ->findAll();

        echo json_encode($projection);
    }

    /** 
     * Fetches all projections in a specific cinema
     * @return JSON
     */

    public function getAllProjections() {
        $cinemaEmail = $_REQUEST['cinemaEmail'];

        $projectionsModel = new ProjectionModel();

        $projections = $projectionsModel
                                    ->where('email', $cinemaEmail)
                                    ->where('canceled <', 1)
                                    ->join('Movies', 'Projections.tmdbID = Movies.tmdbID')
                                    ->findAll();

        echo json_encode($projections);
    }

    /** 
     * Marks the seat as taken when selling a ticket
     * @return JSON
     */

    public function confirm() {

        $idPro = $_POST['idPro'];
        $cinemaEmail = $_POST['cinemaEmail'];
        $message = "OK";

        $reservationModel = new ReservationModel();
        $seatModel = new SeatModel();
    
        $pairs = explode(' ', $_POST['seats']);

        $db = db_connect();
        

        $seat = null;
        try {
            $db->transStart();
            foreach($pairs as $pair) {
                preg_match('/([0-9]{1,2}):([0-9]{1,2})/', $pair, $resArr);

                // Check if seats are still free
                $foundSeat = $seatModel
                                    ->where('idPro', $idPro)
                                    ->where('rowNumber', $resArr[1])
                                    ->where('seatNumber', $resArr[2])
                                    ->findAll();
                
                if($foundSeat[0]->status != 'free') {
                    throw new Exception('Seat taken!');
                }

                $seatModel->where([
                    'idPro' => $idPro, 
                    'rowNumber' => $resArr[1], 
                    'seatNumber' => $resArr[2]
                    ])->set([
                    'status' => 'sold'        
                    ])->update();
            }
            $db->transCommit();
        } catch (Exception $e) {
            $message = "BAD";
        }
        
        echo json_encode($message);
    }

    /** 
     * Gets the seats that are taken
     * @return JSON
     */

    public function getProjSeats() {
        $idPro = $_REQUEST['idPro'];

        $seatModel = new SeatModel();

        $seats = $seatModel
                        ->where('idPro', $idPro)
                        ->where('status !=', 'free')
                        ->findAll();

        echo json_encode($seats);
    }
}