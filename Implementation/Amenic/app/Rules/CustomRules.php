<?php namespace App\Rules;

/*
    Author: Andrija Kolić
    Github: k0lic
*/

use App\Models\TechnologyModel;
use App\Models\RoomModel;
use App\Models\RoomTechnologyModel;
use App\Models\ProjectionModel;
use App\Models\ComingSoonModel;
use App\Models\MovieModel;
use App\Models\WorkerModel;

date_default_timezone_set("Europe/Belgrade");

class CustomRules
{

    private string $userMail = "cinemaMail";

    // Always throws an error.
    public function shouldNotExist($str,&$error = null)
    {
        $error = "This field should be left empty";
        return false;
    }

    // Checks if an array containing only valid idTechs was passed.
    public function checkRoomTech($arr,&$error = null)
    {
        if (count($arr)<1)
        {
            $error = "You must select at least one technology";
            return false;
        }
        $techIds = [];
        $model = new TechnologyModel();
        for ($i=0;$i<count($arr);$i++)
        {
            if (!isset($arr[$i]))
            {
                $error = "You must select only existing technologies";
                return false;
            }
            $x = intval($arr[$i]);
            if ($model->find($x) == null)
            {
                $error = "You must select only existing technologies";
                return false;
            }
        }
        return true;
    }

    // Checks if a room name is available in this cinema.
    public function checkRoomName($str,&$error = null)
    {
        $model = new RoomModel();
        if ($model->where("email", $this->userMail)->where("name", $str)->find() != null)
        {
            $error = "An existing room already has this name";
            return false;
        }
        return true;
    }

    // Checks if the new room name is available, ignoring the old one.
    public function checkRoomNameExcept($str,&$error = null)
    {
        if (!isset($_POST["oldRoomName"]))
        {
            $error = "";
            return false;
        }
        $oldRoomName = $_POST["oldRoomName"];
        $model = new RoomModel();
        if ($model->where("email", $this->userMail)->where("name", $str)->where("name !=", $oldRoomName)->find() != null)
        {
            $error = "An existing room already has this name";
            return false;
        }
        return true;
    }

    // Checks if a room with the passed name exists.
    public function checkOldRoomName($str,&$error = null)
    {
        $model = new RoomModel();
        if ($model->where("email", $this->userMail)->where("name", $str)->find() != null)
        {
            return true;
        }
        $error = "Cannot find room with name: ".$str.", go back to <a href=\"/Cinema/Rooms\">Rooms</a> please";
        return false;
    }

    // Checks if the passed technology is implemented in the passed room.
    public function checkMovieTech($str,&$error = null)
    {
        if (!isset($_POST["room"]))
        {
            $error = "";
            return false;
        }
        $room = $_POST["room"];
        $model = new RoomTechnologyModel();
        if ($model->where("email", $this->userMail)->where("name", $room)->where("idTech", $str)->find() == null)
        {
            $error = "This technology is not implemented in selected room";
            return false;
        }
        return true;
    }

    // Checks if valid time was passed.
    public function validateTime($str,&$error = null)
    {
        $hh = substr($str, 0, 2);
        $mm = substr($str, 3);

        if (!is_numeric($hh) || !is_numeric($mm))
        {
            $error = "Not numeric";
            return false;
        }
        else if ((int) $hh > 24 || (int) $mm > 59)
        {
            $error = "Invalid time";
            return false;
        }
        else if (mktime((int) $hh, (int) $mm) === FALSE)
        {
            $error = "Invalid time";
            return false;
        }

        return true;
    }

    // Checks if the movie isn't already announced as coming soon, or if projections are already scheduled.
    public function checkIfReallySoon($str,&$error = null)
    {
        $tmdbID = $str;

        $promdl = new ProjectionModel();
        $soonmdl = new ComingSoonModel();
        if ($soonmdl->where("email", $this->userMail)->where("tmdbID", $tmdbID)->find() != null)
        {
            $error = "This movie is already announced as coming soon";
            return false;
        }
        if ($promdl->where("email", $this->userMail)->where("tmdbID", $tmdbID)->where("canceled", 0)->find() != null)
        {
            $error = "This movie is already showing";
            return false;
        }

        return true;
    }

    // Checks if the movies is already announced as coming soon.
    public function checkIfNotSoon($tmdbID,&$error = null)
    {
        $soonmdl = new ComingSoonModel();

        if ($soonmdl->where("email", $this->userMail)->where("tmdbID", $tmdbID)->find() == null)
        {
            $error = "This movie is not in your coming soon list";
            return false;
        }

        return true;
    }

    // Checks if date is in the past.
    public function checkIfDateInThePast($str,&$error = null)
    {
        $date = strtotime($str);
        $now = strtotime("today");

        if ($date < $now)
        {
            $error = "Selected date cannot be in the past";
            return false;
        }

        return true;
    }

    // Checks if datetime is in the past, or less than an hour in the future.
    public function checkIfTimeInThePast($str,&$error = null)
    {
        if (!isset($_POST["startDate"]))
        {
            $error = "";
            return false;
        }
        $time = strtotime($_POST["startDate"]." ".$str);
        $now = time();

        if ($time < $now + 3600)
        {
            $error = "Schedule the projection at least an hour in advance";
            return false;
        }

        return true;
    }

    // Checks if for the chosen room, date and time, there are other projections that would conflict.
    // Doesn't account for any grace time between projections.
    public function checkForCollisions($str,&$error = null)
    {
        $promdl = new ProjectionModel();
        $moviemdl = new MovieModel();

        if (!isset($_POST["startDate"]))
        {
            $error = "";
            return false;
        }
        $timeStart = strtotime($_POST["startDate"]." ".$str);
        // gets data from different sources depending on if a movie is being added or edited
        if (isset($_POST["oldIdPro"]))
        {
            $pro = $promdl->find($_POST["oldIdPro"]);
            $roomName = $pro->roomName;
            $tmdbID = $pro->tmdbID;
        }
        else
        {
            if (!isset($_POST["room"]) || !isset($_POST["tmdbID"]))
            {
                $error = "";
                return false;
            }
            $roomName = $_POST["room"];
            $tmdbID = $_POST["tmdbID"];
        }

        $movie = $moviemdl->find($tmdbID);
        if ($movie == null)
        {
            $error = "";
            return false;
        }
        $runtime = $movie->runtime;
        $timeEnd = $timeStart + $runtime * 60;

        $tStart = date("Y-m-d H:i", $timeStart);
        $tEnd = date("Y-m-d H:i", $timeEnd);

        $potentialCollisions = $promdl->where("email", $this->userMail)->where("roomName", $roomName)->where("canceled", 0)->findAll();

        foreach ($potentialCollisions as $coll)                             // goes through all the other projections in the same room
        {
            if (isset($_POST["oldIdPro"]) && $pro->idPro == $coll->idPro)
                continue;
            // calculates the other projections start and end times
            $collMovie = $moviemdl->find($coll->tmdbID);
            $collRuntime = $collMovie->runtime;

            $collStart = strtotime($coll->dateTime);
            $collEnd = $collStart + $collRuntime * 60;
            // and checks if there is a conflict
            if (($collStart >= $timeStart && $collStart <= $timeEnd) || ($timeStart >= $collStart && $timeStart <= $collEnd))
            {
                $error = "Suggested start time would lead to conflict with ".$collMovie->title." showing at ".$coll->dateTime;
                return false;
            }
        }

        return true;
    }

    // Check if the projections is not canceled or showing in less than an hour.
    public function checkIfProjectionOkToEdit($idPro,&$error = null)
    {
        $promdl = new ProjectionModel();

        $pro = $promdl->find($idPro);

        if ($pro->canceled)
        {
            $error = "Cannot edit a canceled projection";
            return false;
        }

        $originalStart = strtotime($pro->dateTime);
        $now = time();

        if ($originalStart < $now + 3600)
        {
            $error = "Cannot edit a projection that is starting in less than an hour";
            return false;
        }

        return true;
    }

    // Checks if the worker you are trying to delete actually works for you.
    public function isYourWorker($workerEmail,&$error = null)
    {
        $workermdl = new WorkerModel();

        if ($workermdl->where("email", $workerEmail)->where("idCinema", $this->userMail)->find() == null)
        {
            $error = "You cannot delete someone elses worker";
            return false;
        }

        return true;
    }

}