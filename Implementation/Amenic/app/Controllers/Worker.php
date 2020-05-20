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

class Worker extends BaseController
{
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
            $db->transCommit();
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

        header("/HomeController");
    }

    public function index() {

        

        $token = $this->getToken();
        if (is_null($token)) {
            header('Location: /');
            exit();
        }

        $cinemaModel = new CinemaModel();
        $cinema = $cinemaModel->find($token->cinemaEmail);

        // TEST ZONE //
        //die(var_dump($token));
        ///////////////////////////////

        return view('Worker/worker.php', ['cinema' => $cinema, 'actMenu' => 0, 'userImage' => $token->image, "userFullName" => "$token->firstName $token->lastName"]);
    }

    // RESERVATION LIST //

    public function getReservations() {
        $cinemaEmail = $_REQUEST['cinemaEmail'];

        $phrase = $_REQUEST['phrase'];

        $projectionsModel = new ProjectionModel();

        $projections = $projectionsModel
                            ->where('Projections.email', $cinemaEmail)
                            ->join('Reservations', 'Projections.idPro = Reservations.idPro')
                            
                            //->join('Seats', 'Reservations.idRes = Seats.idRes')
                            //->join('Movies', 'Projections.tmdbID = Movies.tmdbID')
                            //->join('Technologies', 'Projections.idTech = Technologies.idTech')

                            ->join('RUsers', 'Reservations.email = RUsers.email')
                            ->like('RUsers.email',$phrase, $insensitiveSearch = TRUE)
                            ->orLike('firstName',$phrase, $insensitiveSearch = TRUE)
                            ->orLike('lastName',$phrase, $insensitiveSearch = TRUE)
                            ->orLike('Reservations.idRes',$phrase, $insensitiveSearch = TRUE)
                            ->findAll();
                            
        echo json_encode($projections);
    }

    public function getSeats() {
        $idRes = $_REQUEST['idRes'];

        $seatModel = new SeatModel();

        $seats = $seatModel
                    ->where('idRes', $idRes)
                    ->where('status', 'reserved')
                    ->findAll();
                            
        echo json_encode($seats);
    }

    public function getUser() {
        $userEmail = $_REQUEST['userEmail'];

        $ruserModel = new RUserModel();

        $ruser = $ruserModel->find($userEmail);
                            
        echo json_encode($ruser);
    }

    public function getProjection() {
        $idPro= $_REQUEST['idPro'];

        $projectionsModel = new ProjectionModel();

        $projection = $projectionsModel->find($idPro);
                            
        echo json_encode($projection);
    }

    public function getMovie() {
        $tmdbID= $_REQUEST['tmdbID'];

        $movieModel = new MovieModel();

        $movie = $movieModel->find($tmdbID);
                            
        echo json_encode($movie);
    }

    public function getTechnology() {
        $idTech= $_REQUEST['idTech'];

        $technologyModel = new TechnologyModel();

        $technology = $technologyModel->find($idTech);
                            
        echo json_encode($technology);
    }

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

        //$reservationModel->save($reservation);

        echo json_encode("ok");
    }

    public function deleteReservation() {
        $idRes = $_POST['idRes'];

        $seatModel = new SeatModel();
        $seatModel->where('idRes', $idRes)->delete();

        $reservationModel = new ReservationModel();
        $reservationModel->where('idRes', $idRes)->delete();
        
        echo json_encode('ok');
    }

    // RESERVATION MODAL //

    public function getDates() {
        $tmdbID =       $_REQUEST['tmdbID'];
        $cinemaEmail =  $_REQUEST['cinemaEmail'];

        $projectionsModel = new ProjectionModel();

        $dates = $projectionsModel
                            ->where('tmdbID', $tmdbID)
                            ->where('email', $cinemaEmail)
                            ->findAll();

        echo json_encode($dates);
    }

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

    public function getSpecProjection() {
        $idPro = $_REQUEST['idPro'];

        $projectionsModel = new ProjectionModel();

        $projection = $projectionsModel
                                    ->where('Projections.idPro', $idPro)
                                    ->join('Rooms', 'Projections.roomName = Rooms.name')
                                    ->findAll();

        echo json_encode($projection);
    }

    public function getAllProjections() {
        $cinemaEmail = $_REQUEST['cinemaEmail'];

        $projectionsModel = new ProjectionModel();

        $projections = $projectionsModel
                                    ->where('email', $cinemaEmail)
                                    ->join('Movies', 'Projections.tmdbID = Movies.tmdbID')
                                    ->findAll();

        echo json_encode($projections);
    }

    public function confirm() {

        $idPro = $_POST['idPro'];
        $cinemaEmail = $_POST['cinemaEmail'];
        $message = "OK";

        $reservationModel = new ReservationModel();
        $seatModel = new SeatModel();
    
        $pairs = explode(' ', $_POST['seats']);

        $seat = null;
        try {
            foreach($pairs as $pair) {
                preg_match('/([0-9]{1,2}):([0-9]{1,2})/', $pair, $resArr);
    
                $seat = new Seat([
                    'idPro' => $idPro,
                    'rowNumber' => $resArr[1],
                    'seatNumber' => $resArr[2],
                    'status' => 'sold',
                    'idRes' => null
                ]);

                $foundSeat = $seatModel
                        ->where('idPro', $idPro)
                        ->where('rowNumber', $resArr[1])
                        ->where('seatNumber', $resArr[2])
                        ->findAll();

                if(count($foundSeat) == 0) {
                    $seatModel->insert($seat);
                } else {
                    throw new Exception('Seat taken!');
                }
            }
        } catch (Exception $e) {
            $message = "BAD";
        }
        
        echo json_encode($message);
    }

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