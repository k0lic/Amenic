<?php namespace App\Controllers;

/*

    Author: Martin Mitrović
    Github: Rpsaman13000

*/

//models
use \App\Models\UserModel;
use \App\Models\CinemaModel;
use \App\Models\ComingSoonModel;
use \App\Models\AdminModel;
use \App\Models\RUserModel;
use \App\Models\CountryModel;
use \App\Models\CityModel;

//libraries
use App\Libraries\Upload;

//entities
use App\Entities\User;
use App\Entities\Admin;

//helpers
use function App\Helpers\isAuthenticated;
use function App\Helpers\isValid;
use function App\Helpers\generateToken;
use function App\Helpers\setToken;

//exceptions
use Exception;

/** AdminController - handles tasks that 'Cinema' account can do
 *  -overview of registered users, ability to delete accounts
 *  -overview of registered cinemas, ability to delete accounts
 *  -overview of cinema requests, ability to accept or delete request
 *  -ability to add new admins
 *  -account settings
 *  @version 1.0
 */
class AdminController extends BaseController
{
    /** Gets the cookie containing basic info of logged user
     * @return view|object AdminBreachMessage user is not looged or is not admin |
     *   $token - contains admins info 
     */
    private function getToken()
    {
        helper('auth');

        if (isset($_COOKIE['token']))
        {
            $tokenCookie = $_COOKIE['token'];   
            $token = isValid($tokenCookie);
            
            if ($token && isAuthenticated("Admin"))
            {
                //get admins picture form db
                $image = (new UserModel())->find($token->email);
                $image = $image->image;
        
                $token->image = $image;
                return $token;
            }
        }
        return null;
    }

    /** Default function for AdminController
     * @return callable function which returns a list of registered users
     */
    public function index()
    {
        return $this->users();
    }

    /** Fetches all registered users from database
     * @return view AdminView using data - [$curActiveMenu, $usersList, $adminInfo]
     */
    public function users()
    {
        $token = $this->getToken();
        if (is_null($token))
            return view('AdminBreachMessage',[]);

        $users = new UserModel();
        $builder = $users->builder();
        //fetching requested fields from database
        $builder->select('Users.email, Users.image, RUsers.firstName, RUsers.lastName, Cities.name as cityName, Countries.name as countryName')
            ->join('RUsers', 'Users.email = RUsers.email')    
            ->join('Cities', 'RUsers.idCity = Cities.idCity', 'left outer')
            ->join('Countries', 'RUsers.idCountry = Countries.idCountry','left outer');
        $data = $builder->get();

        return view('AdminView',['actMenu' => "0", 'data' => $data->getResult(), 'token' => $token]);
    }

    /** Fetches all cinema accounts from database
     * @return view AdminView using data - [$curActiveMenu, $cinemaList, $adminInfo]
     */
    public function cinemas()
    {   
        $token = $this->getToken();
        if (is_null($token))
            return view('AdminBreachMessage',[]);

        $users = new UserModel();
        $builder = $users->builder();
        $builder->select('Users.email, Users.image, Cinemas.name, Cinemas.address, Cinemas.phoneNumber, Cities.name as cityName, Countries.name as countryName')
            ->join('Cinemas', 'Users.email = Cinemas.email AND Cinemas.approved = 1 AND Cinemas.closed = 0')    
            ->join('Cities', 'Cinemas.idCity = Cities.idCity', 'left outer')
            ->join('Countries', 'Cinemas.idCountry = Countries.idCountry','left outer');
        $data = $builder->get();
        
        return view('AdminView',['actMenu' => "1", 'data' => $data->getResult(), 'token' => $token]);
    }

    /** Fetches all cinema requests from database
     * @return view AdminView using data - [$curActiveMenu, $requestList, $adminInfo]
     */
    public function requests()
    {
        $token = $this->getToken();
        if (is_null($token))
            return view('AdminBreachMessage',[]);

        $users = new UserModel();
        $builder = $users->builder();
        $builder->select('Users.email, Users.image, Cinemas.name, Cinemas.address, Cinemas.phoneNumber, Cities.name as cityName, Countries.name as countryName')
            ->join('Cinemas', 'Users.email = Cinemas.email AND Cinemas.approved = 0')    
            ->join('Cities', 'Cinemas.idCity = Cities.idCity', 'left outer')
            ->join('Countries', 'Cinemas.idCountry = Countries.idCountry','left outer');
        $data = $builder->get();
        
        return view('AdminView',['actMenu' => "2", 'data' => $data->getResult(), 'token' => $token]);
    }

    /** Fetches all admins from database
     * @return view AdminView using data - [$curActiveMenu, $adminList, $adminInfo]
     */
    public function admins()
    {
        $token = $this->getToken();
        if (is_null($token))
            return view('AdminBreachMessage',[]);

        $users = new UserModel();
        $builder = $users->builder();
        $builder->select('Users.email, Users.image, Admins.firstName, Admins.lastName')
            ->join('Admins', 'Users.email = Admins.email');
        $data = $builder->get();

        return view('AdminView',['actMenu' => "3", 'data' => $data->getResult(), 'token' => $token]);
    }

    /** Creates a form to allow admin to change his information
     * @return view SettingsView using data - [$adminInfo, $curActiveMenu, $adminImage, $accountType, $accountToken, $errors] 
     */
    public function settings()
    {
        $token = $this->getToken();
        if (is_null($token))
            return view('AdminBreachMessage',[]);

        $data = [
            'firstName' => $token->firstName,
            'lastName' => $token->lastName,
            'email' =>  $token->email,
            ];

        //you have to save admin info twice beacause this page is being used by all users
        return view('SettingsView',['data' => $data, 'actMenu' => "5", 'image' => $token->image, 'userType' => 'Admin', 'token' => $token, 'errors' => '' ]);    
    }

    /** Filters accounts using provided phrase in post request
     * @return json [actMenu, data, token, phrase]
     */
    public function search()
    {
        $token = $this->getToken();
        if (is_null($token))
            return view('AdminBreachMessage',[]);

        if(!isset($_POST['actMenu']) || !isset($_POST['phrase']))
        {
            throw new Exception(get_class($this).".search failed!<br/>Adequate parametres not found");
        }   
        
        $phrase = $_POST['phrase'];
        $actMenu = $_POST['actMenu'];

        $model=null;
        $data=null;

        switch($actMenu)
        {
            case 0:
                $model = new RUserModel();
                $data = $model
                    ->select('Users.email, Users.image, RUsers.firstName, RUsers.lastName, Cities.name as cityName, Countries.name as countryName')
                    ->like('RUsers.email',$phrase, $insensitiveSearch = TRUE)
                    ->orLike('firstName',$phrase, $insensitiveSearch = TRUE)
                    ->orLike('lastName',$phrase, $insensitiveSearch = TRUE)
                    ->join('Users', 'RUsers.email = Users.email')
                    ->join('Cities', 'RUsers.idCity = Cities.idCity', 'left outer')
                    ->join('Countries', 'RUsers.idCountry = Countries.idCountry', 'left outer')
                    ->find();
                
                return json_encode($data);
                //return view('AdminView',['actMenu' => "0", 'data' => $data, 'token' => $token, 'phrase' => $phrase]);
                break;
            case 1:
                $model = new CinemaModel();
                $data = $model
                    ->select('Users.email, Users.image, Cinemas.name, Cinemas.address, Cinemas.phoneNumber, Cities.name as cityName, Countries.name as countryName')
                    ->where('Cinemas.approved', 1)
                    ->like('Cinemas.email',$phrase, $insensitiveSearch = TRUE)
                    ->orWhere('Cinemas.approved', 1)
                    ->like('Cinemas.name',$phrase, $insensitiveSearch = TRUE)
                    ->join('Users', 'Cinemas.email = Users.email')
                    ->join('Cities', 'Cinemas.idCity = Cities.idCity', 'left outer')
                    ->join('Countries', 'Cinemas.idCountry = Countries.idCountry', 'left outer')
                    ->find();
                 
                return json_encode($data);
                //return view('AdminView',['actMenu' => "1", 'data' => $data, 'token' => $token, 'phrase' => $phrase]);
                break;
            case 2:
                $model = new CinemaModel();
                $data = $model
                    ->select('Users.email, Users.image, Cinemas.name, Cinemas.address, Cinemas.phoneNumber, Cities.name as cityName, Countries.name as countryName')
                    ->where('Cinemas.approved = 0')
                    ->like('Cinemas.email',$phrase, $insensitiveSearch = TRUE)
                    ->orWhere('Cinemas.approved = 0')
                    ->like('Cinemas.name',$phrase, $insensitiveSearch = TRUE)
                    ->join('Users', 'Cinemas.email = Users.email')
                    ->join('Cities', 'Cinemas.idCity = Cities.idCity', 'left outer')
                    ->join('Countries', 'Cinemas.idCountry = Countries.idCountry', 'left outer')
                    ->find();
                 
                return json_encode($data);
                //return view('AdminView',['actMenu' => "2", 'data' => $data, 'token' => $token, 'phrase' => $phrase]);
                break;
            case 3:
                $model = new AdminModel();
                $data = $model
                    ->select('Users.email, Users.image, Admins.firstName, Admins.lastName')
                    ->like('Admins.email',$phrase, $insensitiveSearch = TRUE)
                    ->orLike('firstName',$phrase, $insensitiveSearch = TRUE)
                    ->orLike('lastName',$phrase, $insensitiveSearch = TRUE)
                    ->join('Users', 'Admins.email = Users.email')
                    ->find();
                
                return json_encode($data);
                //return view('AdminView',['actMenu' => "3", 'data' => $data, 'token' => $token, 'phrase' => $phrase]);
                break;
            default:
                return $this->index();
                break;
        }


    }

    //HELPER FUNCTIONS
    /** Selects function to call depending on cuurrently active menu
     * @param int $actMenu currently active menu
     * @return callable function which provides adequate view
     */
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

    /** Removes account from database except admin account
     * @return callable function to show desired menu
     */
    public function removeUser()
    {
        $token = $this->getToken();
        if (is_null($token))
            return view('AdminBreachMessage',[]);

        $actMenu= $_POST['actMenu'];
        $key = $_POST['key'];
        unset($_POST['actMenu']);
        unset($_POST['key']);

        $model = null;
        switch($actMenu)
        {
            case "0":
                $model = new RUserModel();
                break;
            case "1":
                $model = new CinemaModel();
                break;
            case "2":
                $model = new CinemaModel();
                break;
            default:
                return $this->selectMenu($actMenu);
        }

        if(isset($key))
        {
            $user = $model->where(['email'=>$key])->findAll();
            
            if(count($user) > 0)
            {
               $model->transSmartDelete($key);               
            }
        }
        
        return $this->selectMenu($actMenu);
    }

    /** Overview of cinema account (approved or not) 
     * @return view AdminRequestView using data [$cinemaData, $actMenu, $countryName, $cityName, $adminData ] - 
     */
    public function editRequest()
    {
        $token = $this->getToken();
        if (is_null($token))
            return view('AdminBreachMessage',[]);

        if(!isset($_POST['key']) || !isset($_POST['key']))
            return $this->requests();

        $actMenu= $_POST['actMenu'];
        $key = $_POST['key'];
        unset($_POST['key']);
        unset($_POST['actMenu']);

        $data = (new CinemaModel())->find($key);
        $county = (new CountryModel())->find($data->idCountry);
        $city = (new CityModel())->find($data->idCity);

        return view("AdminRequestView",["data" => $data, "actMenu" => $actMenu, "country" => $county->name, "city" => $city->name, "token" => $token ]);
    }

    /** Approves cinema request in database
     * @return callable function to show desired menu
     */
    public function approveCinema()
    {
        $token = $this->getToken();
        if (is_null($token))
            return view('AdminBreachMessage',[]);

        $actMenu= $_POST['actMenu'];
        $key = $_POST['key'];
        unset($_POST['actMenu']);
        unset($_POST['key']);

        $cinema = (new CinemaModel())->where(['email' => $key])->set(['approved' => 1])->update();

        return $this->selectMenu($actMenu);
    }

    /** Reopens cinema account
     * @return callable function to show desired menu
     */
    public function openCinema()
    {
        $token = $this->getToken();
        if (is_null($token))
            return view('AdminBreachMessage',[]);

        $actMenu= $_POST['actMenu'];
        $key = $_POST['key'];
        unset($_POST['actMenu']);
        unset($_POST['key']);

        $cinema = (new CinemaModel())->where(['email' => $key])->set(['closed' => 0])->update();
        return $this->selectMenu($actMenu);
    }

    /** Saves changes to admin account
     * @return callable function to show desired menu
     */
    public function saveSettings()
    {
        $token = $this->getToken();
        if (is_null($token))
            return view('AdminBreachMessage',[]);        

        $validation =  \Config\Services::validation();
        $db = db_connect();

        //fetching data
        $fName = $_POST['fName'];
        $lName = $_POST['lName'];
        $email = $token->email;
        $pswdOld = $_POST['pswdOld'];
        $pswdNew = $_POST['pswdNew'];
        $image = $this->request->getFile('profilePicture');
        
        $form = [
            'fName' => $fName,
            'lName' => $lName,
            'email' => $email,
            'pswd' => [ 
                'oldPswd' => $pswdOld,
                'newPswd' => $pswdNew,
                'email' => $email
            ],
            'profilePicture' => $image
        ]; 
            
        $valid = $validation->run($form, "adminSettingsCheck");
    
        if($valid != 1)
        {
            
            $data = [
                'firstName' => $token->firstName,
                'lastName' => $token->lastName,
                'email' =>  $token->email,
                ];
            $errors = $validation->getErrors();

            return view('SettingsView',['data' => $data, 'actMenu' => 5, 'image' => $token->image, 'userType' => 'Admin', 'token' => $token, 'errors' => $errors ]);    
        }

        //password remains the same
        if(strcmp($pswdNew,"") == 0)
        {
            $pswdNew = (new UserModel())->find($email)->password;
        }
        else
        {
            $pswdNew = password_hash($pswdNew,PASSWORD_BCRYPT, ['cost' => 8]);
        }
        
        try
        {
            //update database
            $db->transBegin();
            $img=null;
            if (strcmp($image->getName(),"") !=0)
            {
                $img = base64_encode(file_get_contents($image));
                (new UserModel())->where(['email' => $email])->set([
                    'image' => $img,             
                    ])->update();            
            }

            (new UserModel())->where(['email' => $email])->set([
                'password' => $pswdNew               
                ])->update();
            (new AdminModel())->where(['email' => $email])->set(['firstName' => $fName, 'lastName' => $lName])->update();
            $db->transCommit();
        }
        catch (Exception $e)
        {
            $db->transRollback();
            throw new Exception("Transaction ".get_class($this).".saveChanges(".$email.") failed!<br/>".$e->getMessage());
        }

        //change token
        $payload = [
            "firstName" => $fName,
            "lastName" => $lName,
            "email" => $email,
            "type" => "Admin"
        ]; 
        setToken(generateToken($payload));
        $token = $this->getToken();

        return $this->selectMenu(3);
    }

    /** Creates new admin in database
     * @return callable function to show desired menu
     */
    public function addAdmin()
    {
        $token = $this->getToken();
        if (is_null($token))
            return view('AdminBreachMessage',[]);

        $validation =  \Config\Services::validation();
        $db = db_connect();
        
        $fNameNA = $_POST['fNameNA'];
        $lNameNA = $_POST['lNameNA'];
        $emailNA = $_POST['emailNA'];
        $passwordNA = $_POST['passwordNA'];
        $passwordConfirmNA = $_POST['passwordConfirmNA'];

        $form = [
            'fNameNA' => $fNameNA,
            'lNameNA' => $lNameNA,
            'emailNA' => $emailNA,
            'passwordNA' => $passwordNA,
            'passwordConfirmNA' => $passwordConfirmNA
        ];

        $valid = $validation->run($form, "adminAccountCheck");

        if($valid != 1)
        {
            $data = (new UserModel())
            ->join('Admins', 'Users.email = Admins.email')
            ->find();
            $errors = $validation->getErrors();
            
            return view('AdminView',['actMenu' => "3", 'data' => $data, 'token' => $token, 'errors' => $errors, 'form' => $form]);
        }

        $passwordNA = password_hash($passwordNA,PASSWORD_BCRYPT, ['cost' => 8]);

        try
        {
            $db->transBegin();
            $user = new User([
                'email' => $emailNA,
                'password' => $passwordNA,
                'image' => null
            ]);
            $userModel = new UserModel();
            $userModel->insert($user);
            
            $adminModel = new AdminModel();
            $admin = new Admin([
                'email' => $emailNA,
                'firstName' => $fNameNA,
                'lastName' => $lNameNA
            ]);
            $adminModel->insert($admin);
            $db->transCommit();
        } catch(Exception $e) {
            $db->transRollback();
            throw new Exception("Transaction ".get_class($this).".addAdmin(".$email.") failed!<br/>".$e->getMessage());
        } 

        return $this->selectMenu(3);
    }

    /** Closes cinema account 
     * @return callable function that logs out user
     */
    public function closeCinema()
    {
        $email = $_POST['key'];
        unset($_POST['key']);

        try
        {
            // old Martins code
                //$cinema = (new CinemaModel())->where(['email' => $email])->set(['closed' => 1])->update();
            // my new code
                (new CinemaModel())->transSmartClose($email);
            // end
        } catch(Exception $e) {
            echo $e->getMessage();
        }
        return redirect()->to('/Logout');
    }
}