<?php namespace App\Controllers;

/*
    Author: Andrija Kolić
    Github: k0lic
*/

use App\Models\AACinemaModel;
use App\Models\RoomModel;
use App\Models\WorkerModel;
use App\Models\TechnologyModel;
use App\Models\ProjectionModel;
use App\Models\MovieModel;
use App\Models\RoomTechnologyModel;
use App\Entities\Room;
use Exception;

/*
    This controller handles most if not all tasks that a user of type 'Cinema' can use.
    All data used is for the logged in Cinema account.
*/
class Cinema extends BaseController
{

    //--------------------------------------------------------------------
    //  FIELDS  //
    //--------------------------------------------------------------------

    // Hard coding the email for now.
    private string $userMail = "cinemaMail";

    //--------------------------------------------------------------------
    //  PUBLIC METHODS  //
    //--------------------------------------------------------------------

    // Shows all the projections. 
    public function index()
    {
        $projectionsWithPosters = (new AACinemaModel())->findAllProjectionsOfMyCinemaAndAttachPosters($this->userMail);
        return view("Cinema/CinemaOverview.php",["items" => $projectionsWithPosters,"optionPrimary" => 0,"optionSecondary" => 0]);
    }

    // Shows the movies that are coming soon.
    public function comingSoon()
    {
        $soonsWithPosters = (new AACinemaModel())->findAllComingSoonsOfMyCinemaAndAttachPosters($this->userMail);
        return view("Cinema/CinemaOverview.php",["items" => $soonsWithPosters,"optionPrimary" => 0,"optionSecondary" => 1]);
    }

    // Shows all the rooms.
    public function rooms()
    {
        $rooms = (new RoomModel())->where("email",$this->userMail)->findAll();
        return view("Cinema/CinemaOverview.php",["items" => $rooms,"optionPrimary" => 1]);
    }

    // Shows all the employees.
    public function employees()
    {
        $employees = (new WorkerModel())->where("idCinema",$this->userMail)->findAll();
        return view("Cinema/CinemaOverview.php",["items" => $employees,"optionPrimary" => 2]);
    }

    // Presents the form for adding a new projection.
    public function addMovie()
    {
        $rooms = (new RoomModel())->where("email",$this->userMail)->findAll();
        $technologies = (new TechnologyModel())->findAll();
        return view("Cinema/CinemaAddMovie.php",["rooms" => $rooms,"technologies" => $technologies,"optionPrimary" => 0]);
    }

    // Presents the form for editing an existing projection.
    public function editMovie($idPro)
    {
        $rooms = (new RoomModel())->where("email",$this->userMail)->findAll();
        $technologies = (new TechnologyModel())->findAll();
        $projection = (new ProjectionModel())->find($idPro);
        $movie = (new MovieModel())->find($projection->tmdbID);
        return view("Cinema/CinemaAddMovie.php",["rooms" => $rooms,"technologies" => $technologies,"target" => $projection,"targetName" => $movie->title,"optionPrimary" => 0]);
    }

    // Presents the form for editing a movie that is coming soon.
    public function editComingSoon()
    {
        throw new Exception("Not yet implemented!");
    }

    // Presents the form for adding a new room.
    public function addRoom()
    {
        $technologies = (new TechnologyModel())->findAll();
        return view("Cinema/CinemaAddRoom.php",["technologies" => $technologies,"optionPrimary" => 1]);
    }

    // Presents the form for editing an existing room.
    public function editRoom($name)
    {
        $name = str_replace("%20"," ",$name);
        $technologies = (new TechnologyModel())->findAll();
        $targetTechnologies = (new RoomTechnologyModel())->where("email",$this->userMail)->where("name",$name)->findColumn("idTech");
        $room = (new RoomModel())->where("email",$this->userMail)->where("name",$name)->findAll();
        if ($room == null)
            return view("404.php");
        return view("Cinema/CinemaAddRoom.php",["technologies" => $technologies,"target" => $room[0],"targetTechnologies" => $targetTechnologies,"optionPrimary" => 1]);
    }

    // Adds a new projection or edits an existing one (or edits a coming soon movie), depending on the POST parameters.
    public function actionAddMovie()
    {
        throw new Exception("NOT YET IMPLEMENTED!<br/>>");
    }

    // Cancels an existing projection.
    public function actionCancelMovie()
    {
        throw new Exception("NOT YET IMPLEMENTED!<br/>>");
    }

    // Adds a new room.
    public function actionAddRoom()
    {
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

    // Edits an existing room.
    public function actionEditRoom()
    {
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

    // Removes an existing room.
    public function actionRemoveRoom()
    {
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

    //--------------------------------------------------------------------
    //  PRIVATE METHODS  //
    //--------------------------------------------------------------------
    
    // Calls the validation service test named $testName to check validity of $data.
    private function isValid($testName, $data)
    {
        $validation =  \Config\Services::validation();

        $ret = $validation->run($data, $testName);

        if ($ret == 1)
            return 1;
        return $validation->getErrors();
    }

    // Checks if the request is of POST type and if it's not, reroutes home.
    private function goHomeIfNotPost()
    {
        if($_SERVER["REQUEST_METHOD"] != "POST") {
            // Unauthorized GET request
            header("Location: /Cinema");
            exit();
        }
    }
}

?>