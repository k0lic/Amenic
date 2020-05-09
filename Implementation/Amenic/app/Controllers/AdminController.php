<?php namespace App\Controllers;

use \App\Models\UserModel;
use \App\Models\CinemaModel;
use \App\Models\ComingSoonModel;
use \App\Models\AdminModel;
use \App\Models\RUserModel;
use \App\Models\CountryModel;
use \App\Models\CityModel;
use App\Libraries\Upload;
use App\Entities\User;
use App\Entities\Admin;

class AdminController extends BaseController
{
    public function index()
    {
        return $this->users();
    }

    public function users()
    {   
        $users = new UserModel();
        $builder = $users->builder();
        $builder->select('Users.email, Users.image, RUsers.firstName, RUsers.lastName, Cities.name as cityName, Countries.name as countryName')
            ->join('RUsers', 'Users.email = RUsers.email')    
            ->join('Cities', 'RUsers.idCity = Cities.idCity', 'left outer')
            ->join('Countries', 'RUsers.idCountry = Countries.idCountry','left outer');
        $data = $builder->get();
        
        return view('AdminView',['actMenu' => "0", 'data' => $data]);
    }

    public function cinemas()
    {   
        $users = new UserModel();
        $builder = $users->builder();
        $builder->select('Users.email, Users.image, Cinemas.name, Cinemas.address, Cinemas.phoneNumber, Cities.name as cityName, Countries.name as countryName')
            ->join('Cinemas', 'Users.email = Cinemas.email AND Cinemas.approved = 1 AND Cinemas.closed = 0')    
            ->join('Cities', 'Cinemas.idCity = Cities.idCity', 'left outer')
            ->join('Countries', 'Cinemas.idCountry = Countries.idCountry','left outer');
        $data = $builder->get();
        
        return view('AdminView',['actMenu' => "1", 'data' => $data]);
    }

    public function requests()
    {
        $users = new UserModel();
        $builder = $users->builder();
        $builder->select('Users.email, Users.image, Cinemas.name, Cinemas.address, Cinemas.phoneNumber, Cities.name as cityName, Countries.name as countryName')
            ->join('Cinemas', 'Users.email = Cinemas.email AND Cinemas.approved = 0')    
            ->join('Cities', 'Cinemas.idCity = Cities.idCity', 'left outer')
            ->join('Countries', 'Cinemas.idCountry = Countries.idCountry','left outer');
        $data = $builder->get();
        
        return view('AdminView',['actMenu' => "2", 'data' => $data]);
    }

    public function admins()
    {
        $users = new UserModel();
        $builder = $users->builder();
        $builder->select('Users.email, Users.image, Admins.firstName, Admins.lastName')
            ->join('Admins', 'Users.email = Admins.email');
        $data = $builder->get();

        return view('AdminView',['actMenu' => "3", 'data' => $data]);
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

    public function settings()
    {
        $data = (new AdminModel())->find('andrija@gmail.com');
        $image = (new UserModel())->find('andrija@gmail.com')->image;
        return view('AdminSettingsView',['data' => $data, 'actMenu' => 5, 'image' => $image]);    
    }

    public function saveSettings()
    {
        $name = $_POST['fName'];
        $lName = $_POST['lName'];
        $mail = $_POST['email'];
        $pswd = $_POST['pswd'];
        $pswdR = $_POST['pswdR'];

        $mail = $_POST['email'];
        $file = $this->request->getFile('profilePicture');
        if (strcmp($file->getName(),"") !=0)
        {
            $img = base64_encode(file_get_contents($file));
            (new UserModel())->where(['email' => $mail])->set([
                'image' => $img,             
                ])->update();            
        }
        (new UserModel())->where(['email' => $mail])->set([
            'password' => $pswd               
            ])->update();
        (new AdminModel())->where(['email' => $mail])->set(['firstName' => $name, 'lastName' => $lName])->update();
        return $this->selectMenu(3);
    }

    public function addAdmin()
    {
        $fName = $_POST['fNameNA'];
        $lName = $_POST['lNameNA'];
        $email = $_POST['emailNA'];
        $passwordNA = $_POST['passwordNA'];
        $passwordNA = password_hash($passwordNA,PASSWORD_BCRYPT, ['cost' => 8]);

        if (is_null($fName) || is_null($lName) || is_null($email) || is_null($passwordNA))
            return $this->selectMenu(3);

        $user = new User([
            'email' => $email,
            'password' => $passwordNA,
            'image' => null
        ]);
        $userModel = new UserModel();
        if ($userModel->insert($user)) {
			echo 'failed<br/>'.$userModel->errors();
        } 
   
        $adminModel = new AdminModel();
        $admin = new Admin([
            'email' => $email,
            'firstName' => $fName,
            'lastName' => $lName
        ]);
        if ($adminModel->insert($admin)) {
			echo 'failed<br/>'.$userModel->errors();
        } 

        return $this->selectMenu(3);
    }
}