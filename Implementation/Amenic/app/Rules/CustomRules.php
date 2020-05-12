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

date_default_timezone_set("Europe/Belgrade");

class CustomRules
{

    private string $userMail = "cinemaMail";

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
    public function checkIfReallysoon($str,&$error = null)
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

    public function checkIfTimeInThePast($str,&$error = null)
    {
        $time = strtotime($_POST["startDate"]." ".$str);
        $now = time();

        if ($time < $now + 3600)
        {
            $error = "Schedule the projection at least an hour in advance";
            return false;
        }

        return true;
    }

    public function checkForCollisions($str,&$error = null)
    {
        $promdl = new ProjectionModel();
        $moviemdl = new MovieModel();

        $timeStart = strtotime($_POST["startDate"]." ".$str);
        $roomName = $_POST["room"];
        $tmdbID = $_POST["tmdbID"];
        $movie = $moviemdl->find($tmdbID);
        $runtime = $movie->runtime;
        $timeEnd = $timeStart + $runtime * 60;

        $tStart = date("Y-m-d H:i", $timeStart);
        $tEnd = date("Y-m-d H:i", $timeEnd);

        $potentialCollisions = $promdl->where("email", $this->userMail)->where("roomName", $roomName)->where("canceled", 0)->findAll();

        foreach ($potentialCollisions as $coll)
        {
            $collMovie = $moviemdl->find($coll->tmdbID);
            $collRuntime = $collMovie->runtime;

            $collStart = strtotime($coll->dateTime);
            $collEnd = $collStart + $collRuntime * 60;

            if (($collStart >= $timeStart && $collStart <= $timeEnd) || ($timeStart >= $collStart && $timeStart <= $collEnd))
            {
                $error = "Suggested start time would lead to collision with ".$collMovie->title." showing at ".$coll->dateTime;
                return false;
            }
        }

        return true;
    }

    /*
    public function checkFalseOrTrue($str,&$error = null)
    {
        if ($strcasecmp($str, "true") != 0 && $strcasecmp($str, "false") != 0)
        {
            $error = "Invalid value, expected: 'true' or 'false'";
            return false;
        }
        return true;
    }*/

}