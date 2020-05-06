<?php namespace App\Controllers;

use \App\Models\UserModel;
use \App\Models\CinemaModel;
use \App\Models\RequestModel;
use \App\Models\AdminModel;

class AdminController extends BaseController
{
    public function index()
    {
        return $this->users();
    }

    public function users()
    {
        $users = (new UserModel())->findAll();
        return view('AdminView',['actMenu' => "0", 'data' => $users]);
    }

    public function cinemas()
    {
        $cinemas = (new CinemaModel())->findAll();
        return view('AdminView',['actMenu' => "1", 'data' => $cinemas]);
    }

    public function requests()//cinemas koji nisu odobreni
    {
        $requests = (new RequestModel())->findAll();
        return view('AdminView',['actMenu' => "2", 'data' => $requests]);
    }

    public function admins()
    {
        $admins = (new AdminModel())->findAll();
        return view('AdminView',['actMenu' => "3", 'data' => $admins]);
    }
}