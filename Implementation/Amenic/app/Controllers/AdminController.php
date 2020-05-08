<?php namespace App\Controllers;

use \App\Models\UserModel;
use \App\Models\CinemaModel;
use \App\Models\ComingSoonModel;
use \App\Models\AdminModel;
use \App\Models\RUserModel;
use \App\Models\CountryModel;
use \App\Models\CityModel;


class AdminController extends BaseController
{
    public function index()
    {
        return $this->users();
    }

    public function users()
    {   
        $users = (new RUserModel())->findAll(); 
        return view('AdminView',['actMenu' => "0", 'data' => $users]);
    }

    public function cinemas()
    {   
        $cinemas = (new CinemaModel())->where(['approved' => 1, 'closed' => 0])->find();
        return view('AdminView',['actMenu' => "1", 'data' => $cinemas]);
    }

    public function requests()
    {
        $requests = (new CinemaModel())->where(['approved' => 0])->find();
        return view('AdminView',['actMenu' => "2", 'data' => $requests]);
    }

    public function admins()
    {
        $admins = (new AdminModel())->findAll();
        return view('AdminView',['actMenu' => "3", 'data' => $admins]);
    }

    public function removeUser()
    {
        $actMenu= $_POST['actMenu'];
        $key = $_POST['key'];
        unset($_POST['actMenu']);
        unset($_POST['key']);

        $model = new UserModel();

        if (strcmp($actMenu,"3") == 0)
        {
            echo "POYYY";
            return;
            //return $this->admins();
        }
       
        if(isset($key))
        {
            $user = $model->where(['email'=>$key])->findAll();
            
            if(count($user) > 0)
            {
                $user = $user[0];
                if(strcmp($actMenu,"1") == 0)
                {
                    /*
                    $cinema = new CinemaModel();
                    $cinema->where(['email' => $key])->set(['closed' => 1])->update();*/

                    //removing coming soon
                    $cSoon = (new ComingSoonModel())->where(['email' => $key])->findAll();
                    $deleteModel = new ComingSoonModel();
                    foreach($cSoon as $curMovie)
                    {
                        $deleteModel->delete(['email' => $curMovie->email]);
                    }
                    
                }  
                $model->delete(['email' => $user->email]);   
            }
        }
        return $this->selectMenu($actMenu);
    }

    private function selectMenu($actMenu)
    {
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

    public function editRequest()
    {
        if(!isset($_POST['key']))
            return $this->requests();

        $actMenu= $_POST['actMenu'];
        $key = $_POST['key'];
        unset($_POST['key']);
        unset($_POST['actMenu']);

        $data = (new CinemaModel())->find($key);
        $county = (new CountryModel())->find($data->idCountry);
        $city = (new CityModel())->find($data->idCity);

        return view("AdminRequestView",["data" => $data, "actMenu" => $actMenu, "country" => $county->name, "city" => $city->name ]);
    }

    public function approveCinema()
    {
        $actMenu= $_POST['actMenu'];
        $key = $_POST['key'];
        unset($_POST['actMenu']);
        unset($_POST['key']);

        $cinema = (new CinemaModel())->where(['email' => $key])->set(['approved' => 1])->update();

        return $this->selectMenu($actMenu);
    }

    public function openCinema()
    {
        $actMenu= $_POST['actMenu'];
        $key = $_POST['key'];
        unset($_POST['actMenu']);
        unset($_POST['key']);

        $cinema = (new CinemaModel())->where(['email' => $key])->set(['closed' => 0])->update();
        return $this->selectMenu($actMenu);
    }
}