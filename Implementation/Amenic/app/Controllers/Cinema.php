<?php namespace App\Controllers;

/*
    Author: Andrija Kolić
    Github: k0lic
*/

use App\Models\AACinemaModel;
use App\Models\RoomModel;
use App\Models\WorkerModel;

class Cinema extends BaseController
{
    //hard coding the email for now
    private string $userMail = "cinemaMail";

    public function index()
    {
        $projectionsWithPosters = (new AACinemaModel())->findAllProjectionsOfMyCinemaAndAttachPosters($this->userMail);
        return view("CinemaAccountView.php",["items" => $projectionsWithPosters,"optionPrimary" => 0,"optionSecondary" => 0]);
    }

    public function comingSoon()
    {
        $soonsWithPosters = (new AACinemaModel())->findAllComingSoonsOfMyCinemaAndAttachPosters($this->userMail);
        return view("CinemaAccountView.php",["items" => $soonsWithPosters,"optionPrimary" => 0,"optionSecondary" => 1]);
    }

    public function rooms()
    {
        $rooms = (new RoomModel())->where("email",$this->userMail)->findAll();
        return view("CinemaAccountView.php",["items" => $rooms,"optionPrimary" => 1]);
    }

    public function employees()
    {
        $employees = (new WorkerModel())->where("idCinema",$this->userMail)->findAll();
        return view("CinemaAccountView.php",["items" => $employees,"optionPrimary" => 2]);
    }
}

?>