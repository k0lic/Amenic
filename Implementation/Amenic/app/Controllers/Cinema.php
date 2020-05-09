<?php namespace App\Controllers;

/*
    Author: Andrija Kolić
    Github: k0lic
*/

use App\Models\CompCinemaModel;

class Cinema extends BaseController
{
    //hard coding the email for now
    private string $userMail = "cinemaMail";

    public function index()
    {
        $projectionsWithPosters = (new CompCinemaModel())->findAllProjectionsOfMyCinemaAndAttachPosters($this->userMail);
        return view("CinemaAccountView.php",["items" => $projectionsWithPosters,"optionSecondary" => 0]);
    }

    public function comingSoon()
    {
        $soonsWithPosters = (new CompCinemaModel())->findAllComingSoonsOfMyCinemaAndAttachPosters($this->userMail);
        return view("CinemaAccountView.php",["items" => $soonsWithPosters,"optionSecondary" => 1]);
    }
}

?>