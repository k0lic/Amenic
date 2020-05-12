<?php namespace App\Rules;

/*
    Author: Andrija Kolić
    Github: k0lic
*/

use App\Models\TechnologyModel;
use App\Models\RoomModel;

class CustomRules
{

    private string $userMail = "cinemaMail";

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

}