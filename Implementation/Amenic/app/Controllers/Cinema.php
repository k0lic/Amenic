<?php namespace App\Controllers;

/*
    Author: Andrija Kolić
    Github: k0lic
*/

use \App\Libraries\APIlib;
use \App\Models\UserModel;
use \App\Models\CountryModel;
use \App\Models\CinemaModel;
use \App\Models\CityModel;
use \App\Models\AACinemaModel;
use \App\Models\RoomModel;
use \App\Models\WorkerModel;
use \App\Models\TechnologyModel;
use \App\Models\ProjectionModel;
use \App\Models\MovieModel;
use \App\Models\RoomTechnologyModel;
use \App\Models\ComingSoonModel;
use \App\Entities\Room;
use \App\Entities\Projection;
use \App\Entities\ComingSoon;
use \App\Entities\User;
use \App\Entities\Worker;
use \App\Entities\Movie;
use function \App\Helpers\isAuthenticated;
use function \App\Helpers\isValid;
use function \App\Helpers\generateToken;
use function \App\Helpers\setToken;
use Exception;

/**
 *  This controller handles most if not all tasks that a user of type 'Cinema' can use.
 *  This includes but is not limited to:
 *      -repertoire overview
 *      -repertoire management: adding, editing or canceling projections
 *      -coming soon overview and management
 *      -room overview
 *      -room management: adding, editing or deleting rooms
 *      -employee overview
 *      -employee management: adding or deleting employees
 *      -public cinema page overview - see how the public views your cinema
 *      -public cinema management: gallery and banner management
 *      -settings overview and management
 *  Some of these functionalities are shared with the worker accounts of the cinema,
 *  so worker accounts have access to a few methods in this controller.
 * 
 *  @version 1.0
 */
class Cinema extends BaseController
{

    //--------------------------------------------------------------------
    //  FIELDS  //
    //--------------------------------------------------------------------

    /**
     *  @var string $userMail email address of the logged in account
     */
    private string $userMail = "";

    /**
     *  @var blob $userImage profile picture of the logged in account (can be null)
     */
    private $userImage = null;
    
    /**
     *  @var string $userName the name of the logged in account - displayed next to the profile picture
     */
    private string $userName = "";
    
    /**
     *  @var bool $isWorker flag that is set when this controller is used by a worker account (used to slightly alter the page that is served to workers)
     */
    private bool $isWorker = false;

    //--------------------------------------------------------------------
    //  PUBLIC METHODS  //
    //--------------------------------------------------------------------

    /**
     *  The cinema account homepage.
     *  Shows all the projections.
     *  Accessible by workers.
     * 
     *  @return view Cinema/CinemaOverview
     */
    public function index()
    {
        $this->goHomeIfNotCinemaOrWorkingForCinema();
        $projectionsWithPosters = (new AACinemaModel())->findAllProjectionsOfMyCinemaAndAttachPosters($this->userMail);
        return view("Cinema/CinemaOverview.php",["items" => $projectionsWithPosters,"optionPrimary" => 0,"optionSecondary" => 0,"userImage" => $this->userImage,"userFullName" => $this->userName,"isWorker" => $this->isWorker]);
    }

    /**
     *  Shows the movies that are coming soon.
     *  Accessible by workers.
     * 
     *  @return view Cinema/CinemaOverview 
     */
    public function comingSoon()
    {
        $this->goHomeIfNotCinemaOrWorkingForCinema();
        $soonsWithPosters = (new AACinemaModel())->findAllComingSoonsOfMyCinemaAndAttachPosters($this->userMail);
        return view("Cinema/CinemaOverview.php",["items" => $soonsWithPosters,"optionPrimary" => 0,"optionSecondary" => 1,"userImage" => $this->userImage,"userFullName" => $this->userName,"isWorker" => $this->isWorker]);
    }

    /**
     *  Shows all the rooms.
     * 
     *  @return view Cinema/CinemaOverview
     */
    public function rooms()
    {
        $this->goHomeIfNotCinema();
        $rooms = (new RoomModel())->where("email",$this->userMail)->findAll();
        return view("Cinema/CinemaOverview.php",["items" => $rooms,"optionPrimary" => 1,"userImage" => $this->userImage,"userFullName" => $this->userName,"isWorker" => $this->isWorker]);
    }

    /**
     *  Shows all the employees.
     * 
     *  @return view Cinema/CinemaOverview
     */
    public function employees()
    {
        $this->goHomeIfNotCinema();
        $employees = (new WorkerModel())->getMyWorkersWithImages($this->userMail);
        return view("Cinema/CinemaOverview.php",["items" => $employees,"optionPrimary" => 2,"userImage" => $this->userImage,"userFullName" => $this->userName,"isWorker" => $this->isWorker]);
    }

    /**
     *  Presents the form for adding a new projection.
     *  Accessible by workers.
     * 
     *  @return view Cinema/CinemaAddMovie
     */
    public function addMovie()
    {
        $this->goHomeIfNotCinemaOrWorkingForCinema();
        $rooms = (new RoomModel())->where("email",$this->userMail)->findAll();
        $technologies = (new TechnologyModel())->findAll();
        return view("Cinema/CinemaAddMovie.php",["rooms" => $rooms,"technologies" => $technologies,"optionPrimary" => 0,"userImage" => $this->userImage,"userFullName" => $this->userName,"isWorker" => $this->isWorker]);
    }

    /**
     *  Presents the form for editing an existing projection.
     *  Accessible by workers.
     * 
     *  @return view Cinema/CinemaAddMovie
     */
    public function editMovie($idPro)
    {
        $this->goHomeIfNotCinemaOrWorkingForCinema();
        $rooms = (new RoomModel())->where("email",$this->userMail)->findAll();
        $technologies = (new TechnologyModel())->findAll();
        $projection = (new ProjectionModel())->find($idPro);
        if ($projection == null)
            return view("404.php");
        $movie = (new MovieModel())->find($projection->tmdbID);
        return view("Cinema/CinemaAddMovie.php",["rooms" => $rooms,"technologies" => $technologies,"target" => $projection,"targetName" => $movie->title,"optionPrimary" => 0,"userImage" => $this->userImage,"userFullName" => $this->userName,"isWorker" => $this->isWorker]);
    }

    /**
     *  Presents the form for editing a movie that is coming soon.
     *  Accessible by workers.
     * 
     *  @return view Cinema/CinemaAddMovie
     */
    public function editComingSoon($tmdbID)
    {
        $this->goHomeIfNotCinemaOrWorkingForCinema();
        $rooms = (new RoomModel())->where("email",$this->userMail)->findAll();
        $technologies = (new TechnologyModel())->findAll();
        $soon = (new ComingSoonModel())->where("email", $this->userMail)->where("tmdbID", $tmdbID)->find();
        if ($soon == null)
            return view("404.php");
        $movie = (new MovieModel())->find($tmdbID);
        return view("Cinema/CinemaAddMovie.php",["rooms" => $rooms,"technologies" => $technologies,"halfTarget" => $soon[0],"targetName" => $movie->title,"optionPrimary" => 0,"userImage" => $this->userImage,"userFullName" => $this->userName,"isWorker" => $this->isWorker]);
    }

    /**
     *  Presents the form for adding a new room.
     * 
     *  @return view Cinema/CinemaAddRoom
     */
    public function addRoom()
    {
        $this->goHomeIfNotCinema();
        $technologies = (new TechnologyModel())->findAll();
        return view("Cinema/CinemaAddRoom.php",["technologies" => $technologies,"optionPrimary" => 1,"userImage" => $this->userImage,"userFullName" => $this->userName]);
    }

    /**
     *  Presents the form for editing an existing room.
     * 
     *  @return view Cinema/CinemaAddRoom
     */
    public function editRoom($name)
    {
        $this->goHomeIfNotCinema();
        $name = str_replace("%20"," ",$name);
        $technologies = (new TechnologyModel())->findAll();
        $targetTechnologies = (new RoomTechnologyModel())->where("email",$this->userMail)->where("name",$name)->findColumn("idTech");
        $room = (new RoomModel())->where("email",$this->userMail)->where("name",$name)->findAll();
        if ($room == null)
            return view("404.php");
        return view("Cinema/CinemaAddRoom.php",["technologies" => $technologies,"target" => $room[0],"targetTechnologies" => $targetTechnologies,"optionPrimary" => 1,"userImage" => $this->userImage,"userFullName" => $this->userName]);
    }

    /**
     *  Adds a new projection or adds a movie to the coming soon list.
     *  Accessible by workers.
     *  Accessible only by POST request.
     * 
     *  @return redirects to the appropriate page (/Cinema for current movies; /Cinema/ComingSoon for movies that are coming soon)
     */
    public function actionAddMovie()
    {
        $this->goHomeIfNotCinemaOrWorkingForCinema();
        $this->goHomeIfNotPost();

        $addToSoon = isset($_POST["soon"]);

        $validationResult = $this->isValid($addToSoon?"actionAddSoon":"actionAddMovie", $_POST);
        if ($validationResult == 1)
        {
            if ($addToSoon)
            {
                $tmdbID = $_POST["tmdbID"];

                $soon = new ComingSoon([
                    "tmdbID" => $tmdbID,
                    "email" => $this->userMail
                ]);
                $model = new ComingSoonModel();
            }
            else
            {
                $roomName = $_POST["room"];
                $dateTime = $_POST["startDate"]." ".$_POST["startTime"];
                $price = $_POST["price"];
                $tmdbID = $_POST["tmdbID"];
                $idTech = $_POST["tech"];
    
                $pro = new Projection([
                    "roomName" => $roomName,
                    "email" => $this->userMail,
                    "dateTime" => $dateTime,
                    "price" => $price,
                    "canceled" => 0,
                    "tmdbID" => $tmdbID,
                    "idTech" => $idTech
                ]);
                $model = new ProjectionModel();
            }


            try
            {
                if ($addToSoon)
                    $model->insert($soon);
                else
                    $model->transSmartCreate($pro);
            }
            catch (Exception $e)
            {
                $msg = ($addToSoon?"Adding a movie to coming soon failed!<br/>":"Adding a new movie failed!<br/>").$e->getMessage();
                return view("Exception.php",["msg" => $msg,"destination" => "/Cinema/AddMovie"]);
            }
        }
        else 
        {
            setcookie("addMovieErrors", http_build_query($validationResult), time() + 3600, "/");
            setcookie("addMovieValues", http_build_query($_POST), time() + 3600, "/");
            header("Location: /Cinema/AddMovie");
            exit();
        }

        if ($addToSoon)
            header("Location: /Cinema/ComingSoon");
        else
            header("Location: /Cinema");
        exit();
    }

    /**
     *  Edits an existing projection.
     *  Accessible by workers.
     *  Accessible only by POST request.
     * 
     *  @return redirects to /Cinema
     */
    public function actionEditMovie()
    {
        $this->goHomeIfNotCinemaOrWorkingForCinema();
        $this->goHomeIfNotPost();

        $validationResult = $this->isValid("actionEditMovie", $_POST);
        if ($validationResult == 1)
        {

            $idPro = $_POST["oldIdPro"];
            $dateTime = $_POST["startDate"]." ".$_POST["startTime"];
            $model = new ProjectionModel();

            try
            {
                $model->smartChangeTime($idPro, $dateTime);
            }
            catch (Exception $e)
            {
                $msg = "Editing a movie failed!<br/>".$e->getMessage();
                return view("Exception.php",["msg" => $msg,"destination" => "/Cinema/EditMovie/".$idPro]);
            }
        }
        else 
        {
            setcookie("addMovieErrors", http_build_query($validationResult), time() + 3600, "/");
            setcookie("addMovieValues", http_build_query($_POST), time() + 3600, "/");
            header("Location: /Cinema/EditMovie/".$_POST["oldIdPro"]);
            exit();
        }

        header("Location: /Cinema");
        exit();
    }

    /**
     *  Adds a movie created from the coming soon list.
     *  Creates a new projection using the form input values, and deletes the coming soon entry.
     *  Accessible by workers.
     *  Accessible only by POST request.
     * 
     *  @return redirects to /Cinema
     */
    public function actionReleaseComingSoon()
    {
        $this->goHomeIfNotCinemaOrWorkingForCinema();
        $this->goHomeIfNotPost();

        $validationResult = $this->isValid("actionReleaseSoon", $_POST);
        if ($validationResult == 1)
        {

            $roomName = $_POST["room"];
            $dateTime = $_POST["startDate"]." ".$_POST["startTime"];
            $price = $_POST["price"];
            $tmdbID = $_POST["tmdbID"];
            $idTech = $_POST["tech"];

            $pro = new Projection([
                "roomName" => $roomName,
                "email" => $this->userMail,
                "dateTime" => $dateTime,
                "price" => $price,
                "canceled" => 0,
                "tmdbID" => $tmdbID,
                "idTech" => $idTech
            ]);
            $model = new ProjectionModel();

            try
            {
                $model->transSmartCreate($pro);
            }
            catch (Exception $e)
            {
                $msg = "Releasing a movie from the coming soon list failed!<br/>".$e->getMessage();
                return view("Exception.php",["msg" => $msg,"destination" => "/Cinema/EditComingSoon/".$tmdbID]);
            }
        }
        else 
        {
            setcookie("addMovieErrors", http_build_query($validationResult), time() + 3600, "/");
            setcookie("addMovieValues", http_build_query($_POST), time() + 3600, "/");
            header("Location: /Cinema/EditComingSoon/".$_POST["tmdbID"]);
            exit();
        }

        header("Location: /Cinema");
        exit();
    }

    /**
     *  Cancels an existing projection.
     *  Accessible by workers.
     *  Accessible only by POST request.
     * 
     *  @return redirects to /Cinema
     */
    public function actionCancelMovie()
    {
        $this->goHomeIfNotCinemaOrWorkingForCinema();
        $this->goHomeIfNotPost();

        $validationResult = $this->isValid("actionCancelMovie", $_POST);
        if ($validationResult == 1)
        {

            $idPro = $_POST["oldIdPro"];
            $model = new ProjectionModel();

            try
            {
                $model->transSmartCancel($idPro);
            }
            catch (Exception $e)
            {
                $msg = "Canceling a movie failed!<br/>".$e->getMessage();
                return view("Exception.php",["msg" => $msg,"destination" => "/Cinema/EditMovie/".$idPro]);
            }
        }
        else 
        {
            setcookie("addMovieErrors", http_build_query($validationResult), time() + 3600, "/");
            setcookie("addMovieValues", http_build_query($_POST), time() + 3600, "/");
            header("Location: /Cinema/EditMovie/".$_POST["oldIdPro"]);
            exit();
        }

        header("Location: /Cinema");
        exit();
    }

    /**
     *  Cancels a movie that is announced as coming soon to the cinema.
     *  Accessible by workers.
     *  Accessible only by POST request.
     * 
     *  @return redirects to /Cinema
     */
    public function actionCancelComingSoon()
    {
        $this->goHomeIfNotCinemaOrWorkingForCinema();
        $this->goHomeIfNotPost();

        $validationResult = $this->isValid("actionCancelSoon", $_POST);
        if ($validationResult == 1)
        {

            $tmdbID = $_POST["tmdbID"];
            $model = new ComingSoonModel();

            try
            {
                $model->where("email", $this->userMail)->where("tmdbID", $tmdbID)->delete();
            }
            catch (Exception $e)
            {
                $msg = "Canceling a coming soon movie failed!<br/>".$e->getMessage();
                return view("Exception.php",["msg" => $msg,"destination" => "/Cinema/EditComingSoon/".$tmdbID]);
            }
        }
        else 
        {
            setcookie("addMovieErrors", http_build_query($validationResult), time() + 3600, "/");
            setcookie("addMovieValues", http_build_query($_POST), time() + 3600, "/");
            header("Location: /Cinema/EditComingSoon/".$_POST["tmdbID"]);
            exit();
        }

        header("Location: /Cinema/ComingSoon");
        exit();
    }

    /**
     *  Adds a new room.
     *  Accessible only by POST request.
     * 
     *  @return redirects to /Cinema/Rooms
     */
    public function actionAddRoom()
    {
        $this->goHomeIfNotCinema();
        $this->goHomeIfNotPost();

        $validationResult = $this->isValid("actionAddRoom", $_POST);
        if ($validationResult == 1)
        {
            $roomName = $_POST["roomName"];
            $tech = $_POST["tech"];
            $rows = $_POST["rows"];
            $columns = $_POST["columns"];

            $room = new Room([
                "name" => $roomName,
                "email" => $this->userMail,
                "numberOfRows" => $rows,
                "seatsInRow" => $columns
            ]);
            $model = new AACinemaModel();

            try
            {
                $model->addRoom($room, $tech);
            }
            catch (Exception $e)
            {
                $msg = "Adding a new room failed!<br/>".$e->getMessage();
                return view("Exception.php",["msg" => $msg,"destination" => "/Cinema/AddRoom"]);
            }

        }
        else 
        {
            setcookie("addRoomErrors", http_build_query($validationResult), time() + 3600, "/");
            setcookie("addRoomValues", http_build_query($_POST), time() + 3600, "/");
            header("Location: /Cinema/AddRoom");
            exit();
        }

        header("Location: /Cinema/Rooms");
        exit();
    }

    /**
     *  Edits an existing room.
     *  Accessible only by POST request.
     * 
     *  @return redirects to /Cinema/Rooms
     */
    public function actionEditRoom()
    {
        $this->goHomeIfNotCinema();
        $this->goHomeIfNotPost();

        $validationResult = $this->isValid("actionEditRoom", $_POST);
        if ($validationResult == 1)
        {
            $roomName = $_POST["roomName"];
            $oldRoomName = $_POST["oldRoomName"];
            $tech = $_POST["tech"];
            $rows = $_POST["rows"];
            $columns = $_POST["columns"];

            $room = new Room([
                "name" => $roomName,
                "email" => $this->userMail,
                "numberOfRows" => $rows,
                "seatsInRow" => $columns
            ]);
            $model = new RoomModel();

            try
            {
                $model->transSmartReplace($this->userMail, $oldRoomName, $room, $tech);
            }
            catch (Exception $e)
            {
                $msg = "Editing a room failed!<br/>".$e->getMessage();
                return view("Exception.php",["msg" => $msg,"destination" => "/Cinema/EditRoom/".$roomName]);
            }
        }
        else 
        {
            if (isset($_POST["oldRoomName"]))
            {
                setcookie("addRoomErrors", http_build_query($validationResult), time() + 3600, "/");
                setcookie("addRoomValues", http_build_query($_POST), time() + 3600, "/");
                header("Location: /Cinema/EditRoom/".$_POST["oldRoomName"]);
                exit();
            }
        }

        header("Location: /Cinema/Rooms");
        exit();
    }

    /**
     *  Removes an existing room.
     *  Accessible only by POST request.
     * 
     *  @return redirects to /Cinema/Rooms
     */
    public function actionRemoveRoom()
    {
        $this->goHomeIfNotCinema();
        $this->goHomeIfNotPost();

        $validationResult = $this->isValid("actionRemoveRoom", $_POST);
        if ($validationResult == 1)
        {
            $oldRoomName = $_POST["oldRoomName"];
            $model = new RoomModel();

            try
            {
                $model->transSmartDelete($this->userMail, $oldRoomName);
            }
            catch (Exception $e)
            {
                $msg = "Deleting a room failed!<br/>".$e->getMessage();
                return view("Exception.php",["msg" => $msg,"destination" => "/Cinema/EditRoom/".$oldRoomName]);
            }
        }
        else 
        {
            header("Location: /Cinema/Rooms");
            exit();
        }

        header("Location: /Cinema/Rooms");
        exit();
    }

    /**
     *  Adds a new employee.
     *  Accessible only by POST request.
     * 
     *  @return redirects to /Cinema/Employees
     */
    public function actionAddEmployee()
    {
        $this->goHomeIfNotCinema();
        $this->goHomeIfNotPost();

        $validationResult = $this->isValid("actionAddEmployee", $_POST);
        if ($validationResult == 1)
        {
            $firstName = $_POST["firstName"];
            $lastName = $_POST["lastName"];
            $workerEmail = $_POST["email"];
            $pass = password_hash($_POST["password"], PASSWORD_BCRYPT, ['cost' => 8]);

            $user = new User([
                "email" => $workerEmail,
                "password" => $pass,
                "image" => null
            ]);
            $worker = new Worker([
                "email" => $workerEmail,
                "idCinema" => $this->userMail,
                "firstName" => $firstName,
                "lastName" => $lastName
            ]);
            $model = new WorkerModel();

            try
            {
                $model->transSmartCreate($worker, $user);
            }
            catch (Exception $e)
            {
                $msg = "Adding an employee failed!<br/>".$e->getMessage();
                return view("Exception.php",["msg" => $msg,"destination" => "/Cinema/Employees"]);
            }
        }
        else 
        {
            $retData = [
                "email" => isset($_POST["email"]) ? $_POST["email"] : "",
                "firstName" => isset($_POST["firstName"]) ? $_POST["firstName"] : "",
                "lastName" => isset($_POST["lastName"]) ? $_POST["lastName"] : ""
            ];
            setcookie("addEmployeeErrors", http_build_query($validationResult), time() + 3600, "/");
            setcookie("addEmployeeValues", http_build_query($retData), time() + 3600, "/");
            header("Location: /Cinema/Employees");
            exit();
        }

        header("Location: /Cinema/Employees");
        exit();
    }

    /**
     *  Removes an existing employee.
     *  Accessible only by POST request.
     * 
     *  @return redirects to /Cinema/Employees
     */
    public function actionRemoveEmployee()
    {
        $this->goHomeIfNotCinema();
        $this->goHomeIfNotPost();

        $validationResult = $this->isValid("actionRemoveEmployee", $_POST);
        if ($validationResult == 1)
        {
            $workerEmail = $_POST["email"];
            $model = new WorkerModel();

            try
            {
                $model->transSmartDelete($workerEmail);
            }
            catch (Exception $e)
            {
                $msg = "Removing an employee failed!<br/>".$e->getMessage();
                return view("Exception.php",["msg" => $msg,"destination" => "/Cinema/Employees"]);
            }
        }
        else 
        {
            header("Location: /Cinema/Employees");
            exit();
        }

        header("Location: /Cinema/Employees");
        exit();
    }

    /**
     *  Presents the settings form.
     *  The account settings can be viewed or changed through said form.
     * 
     *  @return view SettingsView
     */
    public function settings()
    {
        $this->goHomeIfNotCinema();
        $token = $this->getToken();
        if (is_null($token))
            return view('AdminBreachMessage',[]);
        
        $city = (new CityModel())->find($token->city);
        $country = (new CountryModel())->find($token->country);
        
        $data = [
            'name' => $token->name,
            'email' =>  $token->email,
            'phoneNumber' => $token->phone,
            'address' => $token->address,
            'city' => $city,
            'country' => $country
        ];
        
        return view('SettingsView',['data' => $data, 'actMenu' => 5, 'image' => $token->image, 'userType' => 'Cinema', 'token' => $token, 'errors' => '']);    
    }

    /**
     *  Edits the account settings using form input values.
     *  Accessible only by POST request.
     * 
     *  @return callable index method of this controller
     */
    public function saveSettings()
    {
        $this->goHomeIfNotCinema();
        $this->goHomeIfNotPost();
        $token = $this->getToken();
        if (is_null($token))
            return view('AdminBreachMessage',[]);        

        $validation =  \Config\Services::validation();
        $db = db_connect();

        //fetching data
        $name = $_POST['name'];
        $email = $token->email;
        $city = $token->city;
        $country = $token->country;
        $phone = $_POST['phone'];
        $address = $_POST['address'];
        $pswdOld = $_POST['pswdOld'];
        $pswdNew = $_POST['pswdNew'];
        $image = $this->request->getFile('profilePicture');
        
        $form = [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'address' => $address,
            'pswd' => [ 
                'oldPswd' => $pswdOld,
                'newPswd' => $pswdNew,
                'email' => $email
            ],
            'profilePicture' => $image
        ]; 
        
        $valid = $validation->run($form, "cinemaInfoCheck");
    
        if($valid != 1)
        {
            $city = (new CityModel())->find($token->city);
            $country = (new CountryModel())->find($token->country);
            
            $data = [
                'name' => $name,
                'email' =>  $token->email,
                'phoneNumber' => $phone,
                'address' => $address,
                'city' => $city,
                'country' => $country
            ];

            $errors = $validation->getErrors();

            return view('SettingsView',['data' => $data, 'actMenu' => 5, 'image' => $token->image, 'userType' => 'Cinema', 'token' => $token, 'errors' => $errors ]);    
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
            (new CinemaModel())->where(['email' => $email])->set(['name' => $name, 'phoneNumber' => $phone, 'address' => $address])->update();
            $db->transCommit();
        }
        catch (Exception $e)
        {
            $db->transRollback();
            throw new Exception("Transaction ".get_class($this).".saveChanges(".$email.") failed!<br/>".$e->getMessage());
        }

        //change token
        $payload = [
            "name" => $name,
            'address' => $address,
            'phone' => $phone,
            'city' => $city,
            'country' => $country,
            "email" => $email,
            "type" => "Cinema"
        ]; 

        setToken(generateToken($payload));
        $token = $this->getToken();

        return $this->index();
    }

    //--------------------------------------------------------------------
    //  FETCH REQUEST METHODS  //
    //--------------------------------------------------------------------

    /**
     *  Fetches the number of movies in our database that match the search term.
     *  Accessible by workers.
     * 
     *  @return JSON number of movies
     */
    public function countHowManyMoviesLike()
    {
        $this->goHomeIfNotCinemaOrWorkingForCinema();
        $match = $_REQUEST["match"];

        $moviemdl = new MovieModel();
        $num = $moviemdl->like("title", $match)->countAllResults();

        echo json_encode($num);
    }

    /**
     *  Fetches the movies in our database that match the search term.
     *  Accessible by workers.
     * 
     *  @return JSON movies that match
     */
    public function getMoviesLike()
    {
        $this->goHomeIfNotCinemaOrWorkingForCinema();
        $match = $_REQUEST["match"];
        $page = $_REQUEST["page"];

        $moviemdl = new MovieModel();
        $results = $moviemdl->limit(20, 20*($page-1))->like("title", $match)->findAll();

        echo json_encode($results);
    }

    /**
     *  Fetches the movies in TMDB that match the search term.
     *  Accessible by workers.
     * 
     *  @return JSON movies that match
     */
    public function getMoviesLikeInTMDB()
    {
        $this->goHomeIfNotCinemaOrWorkingForCinema();
        $match = $_REQUEST["match"];
        $page = $_REQUEST["page"];

        $api = new APIlib();
        $results = $api->getMoviesPage($match, $page);

        echo json_encode($results["body"]);
    }

    /**
     *  Fetches the projections of the users cinema that match the search term.
     *  Matches with title name and room name.
     *  Accessible by workers.
     * 
     *  @return JSON projections that match
     */
    public function getMyProjectionsLike()
    {
        $this->goHomeIfNotCinemaOrWorkingForCinema();
        $match = $_REQUEST["match"];

        $aamdl = new AACinemaModel();
        $results = $aamdl->findAllProjectionsOfMyCinemaLike($this->userMail, $match);

        echo json_encode($results);
    }

    /**
     *  Fetches the movies that are coming soon of the users cinema that match the search term.
     *  Accessible by workers.
     * 
     *  @return JSON movies that match
     */
    public function getMyComingSoonsLike()
    {
        $this->goHomeIfNotCinemaOrWorkingForCinema();
        $match = $_REQUEST["match"];

        $aamdl = new AACinemaModel();
        $results = $aamdl->findAllComingSoonsOfMyCinemaLike($this->userMail, $match);

        echo json_encode($results);
    }

    /**
     *  Fetches the rooms of the users cinema that match the search term.
     * 
     *  @return JSON rooms that match
     */
    public function getMyRoomsLike()
    {
        $this->goHomeIfNotCinema();
        $match = $_REQUEST["match"];

        $roommdl = new RoomModel();
        $results = $roommdl->where("email", $this->userMail)->like("name", $match)->find();

        echo json_encode($results);
    }

    /**
     *  Fetches the employees of the users cinema that match the search term.
     * 
     *  @return JSON employees that match
     */
    public function getMyEmployeesLike()
    {
        $this->goHomeIfNotCinema();
        $match = $_REQUEST["match"];

        $workermdl = new WorkerModel();
        $results = $workermdl->getMyWorkersLikeWithImages($this->userMail, $match);

        echo json_encode($results);
    }

    /**
     *  Adds the selected movie from TMDB to our database.
     *  Accessible by workers.
     * 
     *  @return JSON success
     */
    public function addMovieIfNotExisting()
    {
        $this->goHomeIfNotCinemaOrWorkingForCinema();
        $tmdbID = $_REQUEST["tmdbID"];

        $moviemdl = new MovieModel();
        if ($moviemdl->find($tmdbID) == null) {
            $apilib = new APIlib();

            // get all the information on a film
            $movieBasicInfo = $apilib->getMovieBasic($tmdbID);
            $movieCredits = $apilib->getMovieCredits($tmdbID);
            $movieVideos = $apilib->getMovieVideos($tmdbID);

            // parse the basic info
            $title = $movieBasicInfo[2]['title'];
            $release = $movieBasicInfo[2]['release_date'];
            $runtime = $movieBasicInfo[2]['runtime'];
            $genres="";
            foreach($movieBasicInfo[2]['genres'] as $genre)
                $genres = $genres.$genre['name'].", ";
            $genres = substr ( $genres , 0, strlen($genres)-2 );
            
            // parse the crew
            $directors="";
            $writers="";
            $actors="";
            $numOfActors=0;
            foreach($movieCredits[2]['crew'] as $crew)
            {
                if(strcmp($crew['job'],"Director") == 0)
                    $directors=$directors.$crew['name'].", ";
                if(strcmp($crew['job'],"Writer") == 0 ||
                    strcmp($crew['job'],"Screenplay") == 0 ||
                    strcmp($crew['job'],"Characters") == 0)
                    $writers=$writers.$crew['name'].", ";
            }
            foreach($movieCredits[2]['cast'] as $cast)
            {
                if($numOfActors < 6)
                {
                    $actors=$actors.$cast['name'].", ";
                    $numOfActors++;
                }
                else break;
            }
            $directors = substr($directors,0,strlen($directors)-2 );
            $writers = substr ( $writers , 0, strlen($writers)-2 );
            $actors = substr ( $actors , 0, strlen($actors)-2 );

            // get the plot
            $plot =  $movieBasicInfo[2]['overview'];
            // get the images
            $imageHostingPath = "https://image.tmdb.org/t/p/original/";
            $poster = ($movieBasicInfo[2]["poster_path"] == null) ? null : $imageHostingPath.$movieBasicInfo[2]['poster_path'];
            $backgroundImg = ($movieBasicInfo[2]["backdrop_path"] == null) ? null : $imageHostingPath.$movieBasicInfo[2]['backdrop_path'];

            // get IMDB data
            $imdbID = $movieBasicInfo[2]['imdb_id'];
            $movieOMDBInfo = $apilib->getMovieInfoOMDB($imdbID);
            $imdbRating = $movieOMDBInfo[2];
            if (isset($imdbRating['Ratings']) && count($imdbRating['Ratings']) > 0)
            {
                $imdbRating = $imdbRating['Ratings'][0]['Value'];
            }
            else
                $imdbRating = "";
            $imdbRating = substr($imdbRating, 0, strpos($imdbRating,"/"));

            // get the reviews
            $movieReviews = null;
            $movieReviews = $apilib->getMovieReviews($imdbID);
            $reviews = "DummyAuthor#1#DummyText#0#Dummy2#1#DummyText2";
            if (isset($movieReviews) && $movieReviews != null && isset($movieReviews["firstAuthor"]) && isset($movieReviews["secondAuthor"]) && isset($movieReviews["firstAuthor"]["name"]) && isset($movieReviews["secondAuthor"]["name"]))
            {
                $reviews = $movieReviews["firstAuthor"]["name"]."#1#".$movieReviews["firstAuthor"]["text"]
                    ."#0#".$movieReviews["secondAuthor"]["name"]."#1#".$movieReviews["secondAuthor"]["text"];
            }

            // get trailer
            $trailer="";
            foreach($movieVideos[2]['results'] as $video)
            {
                if (strcmp($video['type'], 'Trailer') == 0)
                {
                    $trailer="https://www.youtube.com/watch?v=".$video['key'];
                    break;
                }
            }
            
            // pack all the data into a Movie object
            $movie = new Movie([
                'tmdbID' => $tmdbID,
                'title' => $title,
                'released' => $release,
                'runtime' => $runtime,
                'genre' => $genres,
                'director' => $directors,
                'writer' => $writers,
                'actors' => $actors,
                'plot' => $plot,
                'poster' => $poster,
                'backgroundImg' =>  $backgroundImg,
                'imdbRating' => $imdbRating,
                'imdbID' => $imdbID,
                'reviews' => $reviews,
                'trailer' => $trailer
            ]);

            // insert new Movie into db
            $movieModel = new MovieModel();
            try
            {
                $movieModel->insert($movie);
                echo json_encode("success");
            }
            catch (Exception $e)
            {
                echo json_encode("failure");
            }
        }
    }

    //--------------------------------------------------------------------
    //  PRIVATE METHODS  //
    //--------------------------------------------------------------------
    
    /**
     *  Calls the validation service test $testName to check validity of $data.
     * 
     *  @param string $testName name of the validation test
     *  @param array $data data which needs to be validated
     *  
     *  @return array errors from the validation testing
     */
    private function isValid($testName, $data)
    {
        $validation =  \Config\Services::validation();

        $ret = $validation->run($data, $testName);

        if ($ret == 1)
            return 1;
        return $validation->getErrors();
    }

    /**
     *  Checks if the request is from a logged in Cinema account by checking the provided token. Redirects to /HomeController if not.
     *  Used to limit access to methods.
     *  Gets relevant account data from the token.
     *  
     *  @return void
     */
    private function goHomeIfNotCinema()
    {
        helper("auth");

        if (isset($_COOKIE["token"]))
        {
            $tokenCookie = $_COOKIE["token"];
            $token = isValid($tokenCookie);
            
            if ($token != null && isAuthenticated("Cinema"))
            {
                $this->userMail = $token->email;
                $this->userName = $token->name;
                $this->userImage = ((new UserModel())->find($token->email))->image;
                return;
            }
        }
        header("Location: /HomeController");
        exit();
    }

    /**
     *  Checks if the request is from a logged in Cinema account, or a logged in Worker account by checking the provided token. Redirects to /HomeController if not.
     *  Used to limit access to methods.
     *  Gets relevant account data from the token.
     *  
     *  @return void
     */
    private function goHomeIfNotCinemaOrWorkingForCinema()
    {
        helper("auth");

        if (isset($_COOKIE["token"]))
        {
            $tokenCookie = $_COOKIE["token"];
            $token = isValid($tokenCookie);
            
            if ($token != null && isAuthenticated("Cinema"))
            {
                $this->userMail = $token->email;
                $this->userName = $token->name;
                $this->userImage = ((new UserModel())->find($token->email))->image;
                return;
            }
            if ($token != null && isAuthenticated("Worker"))
            {
                $this->userMail = ((new WorkerModel())->find($token->email))->idCinema;
                $this->userName = $token->firstName;
                $this->userImage = ((new UserModel())->find($token->email))->image;
                $this->isWorker = true;
                return;
            }
        }
        header("Location: /HomeController");
        exit();
    }

    /**
     *  Checks if the request is of POST type. Redirects to /Cinema if not.
     *  Used to limit access to methods.
     *  
     *  @return void
     */
    private function goHomeIfNotPost()
    {
        if($_SERVER["REQUEST_METHOD"] != "POST") {
            // Unauthorized GET request
            header("Location: /Cinema");
            exit();
        }
    }
    
    /**
     *  Gets the token and appends an image to it.
     *  
     *  @return void
     */
    private function getToken()
    {
        helper('auth');

        if (isset($_COOKIE['token']))
        {
            $tokenCookie = $_COOKIE['token'];   
            $token = isValid($tokenCookie);
            
            if ($token && isAuthenticated("Cinema"))
            {
                $image = (new UserModel())->find($token->email);
                $token->image = $image->image;

                return $token;
            }
        }
        return null;
    }

}

?>