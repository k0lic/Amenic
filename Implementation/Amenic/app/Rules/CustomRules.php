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
use App\Models\UserModel;
use App\Models\CityModel;
use App\Models\CountryModel;
use App\Entities\User;
use function \App\Helpers\isAuthenticated;
use function \App\Helpers\isValid;
use Exception;

date_default_timezone_set("Europe/Belgrade");

class CustomRules
{

    private string $userMail = "";

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
        $this->getUserMail();
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
        $this->getUserMail();
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
        $this->getUserMail();
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
        $this->getUserMail();
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
        $this->getUserMail();
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
        $this->getUserMail();
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
        $this->getUserMail();
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
    }
    
    //SETTINGS FORM VALIDATING RULES

    //Preventing user to force custom place
    //allows empty place
    public function checkPlace($place,&$error = null)
    {
        if (is_null($place))
        {
            $error = "No place provided";
            return false;
        }

        if(isset($place['country']) && strcmp($place['country'],"0") != 0)
        {
            $country = (new CountryModel())->find($place['country']);
            if (is_null($country))
            {
                $error = "Unrecognised country";
                return false;
            }
            if(isset($place['city']) && strcmp($place['city'],"0") != 0)
            {
                $city = (new CityModel())->find($place['city']);
                if (is_null($city))
                {
                    $error = "Unrecognised city";
                    return false;
                }
                else
                {
                    if ($city->idCountry == $country->idCountry)
                        return true;
                    else
                    {
                        $error = "This city doesn't belong to the selected country";
                        return false;
                    }
                }
            }
            return true;
        }
        if(isset($place['city']) && strcmp($place['city'],"0") != 0)
        {
            $error = "Every city requires country";
            return false;
        }

        return true;
    }

    //used for existing users
    public function checkPassword($pswd, &$error = null)
    {
        if (is_null($pswd))
        {
            $error = "Parameter cannot be empty";
            return false;
        }

        if (!isset($pswd['oldPswd']) || !isset($pswd['newPswd']) || !isset($pswd['email']))
        {
            $error = "Parameter doesn't have required fields [oldPswd, newPswd, email]";
            return false;
        }

        try
        {
            $oldPswd = strval($pswd['oldPswd']);
            $newPswd = strval($pswd['newPswd']);
            $email = strval($pswd['email']);
        }
        catch (Exception $e)
        {
            $error = "All parametres must be strings";
            return false;
        }

        //user doesn't want to change password
        if (strcmp($newPswd,"") == 0)
            return true;
        
        if (strcmp($oldPswd,"") == 0)
        {
            $error = "You didn't enter old password";
            return false;
        }
        if (strcmp($newPswd,$oldPswd) == 0)
        {
            $error = "New password cannot be same as the old one";
            return false;
        }
        if(strlen($newPswd) <6)
        {
            $error = "Password must have at least 6 characters";
            return false;
        }

        $basePswd = (new UserModel())->find($email);
        $basePswd = $basePswd->password;

        if (!password_verify($oldPswd,$basePswd))
        {
            $error = "Old password is incorrect";
            return false;
        }
        return true;
    }

    // Checks if the worker you are trying to delete actually works for you.
    public function isYourWorker($workerEmail,&$error = null)
    {
        $this->getUserMail();
        $workermdl = new WorkerModel();

        if ($workermdl->where("email", $workerEmail)->where("idCinema", $this->userMail)->find() == null)
        {
            $error = "You cannot delete someone elses worker";
            return false;
        }

        return true;
    }

    // Gets the logged in users email address, providing the user is logged into a Cinema account.
    // Or, if the user is logged in as a Worker, gets his Cinemas email.
    private function getUserMail()
    {
        helper("auth");

        if (isset($_COOKIE["token"]))
        {
            $tokenCookie = $_COOKIE["token"];
            $token = isValid($tokenCookie);
            
            if ($token != null && isAuthenticated("Cinema"))
            {
                $this->userMail = $token->email;
                return;
            }
            
            if ($token != null && isAuthenticated("Worker"))
            {
                $this->userMail = ((new WorkerModel())->find($token->email))->idCinema;
            }
        }
    }

}