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
use App\Entities\Room;
use Exception;

class Cinema extends BaseController
{
    //hard coding the email for now
    private string $userMail = "cinemaMail";

    public function index()
    {
        $projectionsWithPosters = (new AACinemaModel())->findAllProjectionsOfMyCinemaAndAttachPosters($this->userMail);
        return view("Cinema/CinemaOverview.php",["items" => $projectionsWithPosters,"optionPrimary" => 0,"optionSecondary" => 0]);
    }

    public function comingSoon()
    {
        $soonsWithPosters = (new AACinemaModel())->findAllComingSoonsOfMyCinemaAndAttachPosters($this->userMail);
        return view("Cinema/CinemaOverview.php",["items" => $soonsWithPosters,"optionPrimary" => 0,"optionSecondary" => 1]);
    }

    public function rooms()
    {
        $rooms = (new RoomModel())->where("email",$this->userMail)->findAll();
        return view("Cinema/CinemaOverview.php",["items" => $rooms,"optionPrimary" => 1]);
    }

    public function employees()
    {
        $employees = (new WorkerModel())->where("idCinema",$this->userMail)->findAll();
        return view("Cinema/CinemaOverview.php",["items" => $employees,"optionPrimary" => 2]);
    }

    public function addMovie()
    {
        $rooms = (new RoomModel())->where("email",$this->userMail)->findAll();
        $technologies = (new TechnologyModel())->findAll();
        return view("Cinema/CinemaAddMovie.php",["rooms" => $rooms,"technologies" => $technologies,"optionPrimary" => 0]);
    }

    public function editMovie($idPro)
    {
        $rooms = (new RoomModel())->where("email",$this->userMail)->findAll();
        $technologies = (new TechnologyModel())->findAll();
        $projection = (new ProjectionModel())->find($idPro);
        $movie = (new MovieModel())->find($projection->tmdbID);
        return view("Cinema/CinemaAddMovie.php",["rooms" => $rooms,"technologies" => $technologies,"target" => $projection,"targetName" => $movie->title,"optionPrimary" => 0]);
    }

    public function addRoom()
    {
        $technologies = (new TechnologyModel())->findAll();
        return view("Cinema/CinemaAddRoom.php",["technologies" => $technologies,"optionPrimary" => 1]);
    }

    public function actionAddRoom()
    {
        if($_SERVER["REQUEST_METHOD"] != "POST") {
            // Unauthorized GET request
            header("Location: /Cinema");
            exit();
        }

        $roomName = $_POST["roomName"];
        $oldRoomName = isset($_POST["oldRoomName"])?$_POST["oldRoomName"]:null;
        $tech = $_POST["tech"];
        $rows = $_POST["rows"];
        $columns = $_POST["columns"];

        $room = new Room([
            "name" => $roomName,
            "email" => $this->userMail,
            "numberOfRows" => $rows,
            "seatsInRow" => $columns
        ]);

        $mdl = new AACinemaModel();
        try
        {
            if (isset($oldRoomName))
                $mdl->changeRoom($this->userMail, $oldRoomName, $room, $tech);
            else
                $mdl->addRoom($room, $tech);
        }
        catch (Exception $e)
        {
            $msg = "Adding/Updating room failed!<br/>".$e->getMessage();
            return view("Exception.php",["msg" => $msg,"destination" => "/Cinema/AddRoom"]);
        }
        
        header("Location: /Cinema/Rooms");
        exit();
    }

    public function actionRemoveRoom()
    {

    }
}

?>