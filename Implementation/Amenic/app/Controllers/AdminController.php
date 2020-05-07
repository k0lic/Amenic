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
        $users = (new UserModel())->findAll(); // TODO: Switch back to AdminModel
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

    public function removeUser()
    {
        $actMenu= $_GET['actMenu'];
        $key = $_GET['id'];
        $model = "";
        if (strcmp($actMenu,"0") == 0)
        {
            $model = new UserModel();
        }
        else if (strcmp($actMenu,"3") == 0)
        {
            return $this->admins();
        }
        else
        {
            $model = new CinemaModel();
        }
        if(isset($key))
        {
            $user = $model->find($key);
            if(!is_null($user))
                $model->delete(['eimail' => $user->email]);
        }
        switch($actMenu)
        {
            case "0":
                    return $this->users();
                    break;
            case "1":
                    return $this->cinemas();
                    break;
            case "2":
                    return $this->requests();
                    break;
            case "3":
                    return $this->admins();
                    break;
        }
    }

    public function editUser()
    {
        echo "Ovo je stranica za editovanje Korisnika!";
    }

}