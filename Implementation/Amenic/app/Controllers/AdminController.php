<?php namespace App\Controllers;

/*
    Author: Martin Mitrović
    Github: Rpsaman13000
*/

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

use function App\Helpers\isAuthenticated;
use function App\Helpers\isValid;
use function App\Helpers\generateToken;
use function App\Helpers\setToken;

use Exception;

/** AdminController – klasa koja obradjuje zahteve vezane za "Admin" nalog
 *  -pregled i brisanje registrovanih korisnika
 *  -pregled i brisanje registrovanih bioskopa
 *  -pregled, potvrda i brisanje zahteva za nalog bioskopa
 *  -dodavanje novog admina
 *  -podešavanje postojećeg naloga
 *  @version 1.0
 */
class AdminController extends BaseController
{
    /** Funkcija koja uzima kolačić u kome se nalaze informacije
     *  potrebne za dohvatanje tokena koji sadrži informacije o admin nalogu
     * @return view|object AdminBreachMessage koji obaveštava korisnika da nema privilegije admina |
     *   $token koji sadrži potrebne informacije o adminu 
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
                $image = (new UserModel())->find($token->email);
                $image = $image->image;
        
                $token->image = $image;
                return $token;
            }
        }
    }

    /** Funkcija koja se poziva kada se pozove samo AdminController
     * @return function users koja vraća spisak registrovanih korisnika
     */
    public function index()
    {
        return $this->users();
    }

    /** Funkcija koja dohvata sve registrovane korisnike iz baze i prikazuje ih adminu
     * @return view AdminView sa podacima [$trenutnoAktivniMeni, $podaciZaPrikazivanje, $podaciAdmina]
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

    /** Funkcija koja dohvata sve registrovane bioskope iz baze i prikazuje ih adminu
     * @return view AdminView sa podacima [$trenutnoAktivniMeni, $podaciZaPrikazivanje, $podaciAdmina]
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

    /** Funkcija koja dohvata sve zahteve za registrovanje novih bioskopa
     * @return view AdminView sa podacima [$trenutnoAktivniMeni, $podaciZaPrikazivanje, $podaciAdmina]
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

    /** Funkcija koja dohvata sve admine u sistemu
     * @return view AdminView sa podacima [$trenutnoAktivniMeni, $podaciZaPrikazivanje, $podaciAdmina]
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

    /** Funkcija koja adminu prikazuje formu kojom može da menja svoje podatke osim email adrese
     * @return view SettingsView sa podacima [$podaciZaPrikazivanje, $trenutnoAktivniMeni, $slikaKorisnika, $tipKorisnika]
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
        return view('SettingsView',['data' => $data, 'actMenu' => 5, 'image' => $token->image, 'userType' => 'Admin', 'token' => $token, 'errors' => '' ]);    
    }


    public function search()
    {
        $token = $this->getToken();
        if (is_null($token))
            return view('AdminBreachMessage',[]);

        if(!isset($_POST['actMenu']) || !isset($_POST['phrase']))
        {
            var_dump($_POST['actMenu']);
            var_dump($_POST['phrase']);
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
                 
                return view('AdminView',['actMenu' => "0", 'data' => $data, 'token' => $token, 'phrase' => $phrase]);
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
                 
                return view('AdminView',['actMenu' => "1", 'data' => $data, 'token' => $token, 'phrase' => $phrase]);
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
                 
                return view('AdminView',['actMenu' => "2", 'data' => $data, 'token' => $token, 'phrase' => $phrase]);
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
                 
                return view('AdminView',['actMenu' => "3", 'data' => $data, 'token' => $token, 'phrase' => $phrase]);
                break;
            default:
                return $this->index();
                break;
        }


    }

    //HELPER FUNCTIONS
    /** Funkcija koja šalje admina na željenu funkciju kontrolera u zavisnosti od menija u kom se nalazi
     * @return function koja vraća želejene podatke
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

    /** Funkcija koja uklanja korisnika iz baze nezavisno od toga kog tipa je korisnik. Osim admina naravno
     * @return function koja salje admina na željeni meni
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

        $model = new UserModel();

        //cannot delete other admins
        if (strcmp($actMenu,"3") == 0)
        {
            return $this->admins();
        }
    
        if(isset($key))
        {
            $user = $model->where(['email'=>$key])->findAll();
            
            if(count($user) > 0)
            {
                
                $user = $user[0];
                //deleting a cinema account
                if(strcmp($actMenu,"1") == 0)
                {
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

    /** Funkcija koja daje adminu na pregled nalog bioskopa koji je već potvrdjen ili još nije
     * @return view AdminRequestView [$podaciOBioskopu, $aktivniMeni, $imeDržave, $imeGrada ] - 
     * vraća formu sa svim poslatim podacima i daje adminu mogućnost da potvrdi zahtev, obriše nalog ili otvori zatvoreni bioskop
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

        return view("AdminRequestView",["data" => $data, "actMenu" => $actMenu, "country" => $county->name, "city" => $city->name ]);
    }

    /** Funkcija koja potvrdjuje nalog bioskopa
     * @return function koja salje admina na željeni meni
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

    /** Funkcija koja otvara nalog bioskopa koji je bio zatvoren
     * @return function koja salje admina na željeni meni
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

    /** Funkcija koja pamti promene na nalogu admina
     * @return function koja salje admina na željeni meni
     */
    public function saveSettings()
    {
        $token = $this->getToken();
        if (is_null($token))
            return view('AdminBreachMessage',[]);        

        $validation =  \Config\Services::validation();

<<<<<<< Updated upstream
=======
        $data = [];
        $errors = $validation->run($data,"placeCheck");
        var_dump($errors);
        var_dump($validation->getErrors());

        /*
>>>>>>> Stashed changes
        //fetching data
        $fName = $_POST['fName'];
        $lName = $_POST['lName'];
        $email = $_POST['email'];
        $pswdOld = $_POST['pswdOld'];
        $pswdNew = $_POST['pswdNew'];
<<<<<<< Updated upstream
        $image = $this->request->getFile('profilePicture');
        
=======
        //picture size

>>>>>>> Stashed changes
        $form = [
            'fName' => $fName,
            'lName' => $lName,
            'email' => $email,
<<<<<<< Updated upstream
            'pswd' => [ 
                'oldPswd' => $pswdOld,
                'newPswd' => $pswdNew,
                'email' => $email
            ],
            'profilePicture' => $image
        ]; 
            
        $valid = $validation->run($form, "adminSettingsCheck");
=======
            'pswdOld' => $pswdOld,
        ];
            
        $valid = $validation->run($form, "settingsCheck");
>>>>>>> Stashed changes
    
        if($valid != 1)
        {
            
            $data = [
                'firstName' => $token->firstName,
                'lastName' => $token->lastName,
                'email' =>  $token->email,
                ];
            $errors = $validation->getErrors();
            var_dump($errors);

            return view('SettingsView',['data' => $data, 'actMenu' => 5, 'image' => $token->image, 'userType' => 'Admin', 'token' => $token, 'errors' => $errors ]);    
        }

<<<<<<< Updated upstream
        echo "POYY";
=======
        $valid = $validation->run($pswdNew, $pswdOld, $email, "settingsPasswordCheck");
        var_dump($valid);
        $data = [
            'firstName' => $token->firstName,
            'lastName' => $token->lastName,
            'email' =>  $token->email,
            ];
        $errors = $validation->getErrors();
        
        return view('SettingsView',['data' => $data, 'actMenu' => 5, 'image' => $token->image, 'userType' => 'Admin', 'token' => $token, 'errors' => $errors ]);    
>>>>>>> Stashed changes
        /*
        //password remains the same
        if(strcmp($pswdNew,"") == 0)
        {
            $pswdNew = (new UserModel())->find($email)->password;
        }
<<<<<<< Updated upstream
        
        //update database
=======
        $pswdNew = password_hash($pswdNew,PASSWORD_BCRYPT, ['cost' => 8]);
    
        //image size
        $file = $this->request->getFile('profilePicture');
>>>>>>> Stashed changes
        $img=null;
        if (strcmp($image->getName(),"") !=0)
        {
            $img = base64_encode(file_get_contents($image
        ));
            (new UserModel())->where(['email' => $mail])->set([
                'image' => $img,             
                ])->update();            
        }
        (new UserModel())->where(['email' => $mail])->set([
            'password' => $pswd               
            ])->update();
        (new AdminModel())->where(['email' => $mail])->set(['firstName' => $name, 'lastName' => $lName])->update();
        
        //change token
        $payload = [
            "firstName" => $name,
            "lastName" => $lName,
            "email" => $mail,
            "type" => "Admin"
        ]; 
        setToken(generateToken($payload));
        $token = $this->getToken();

        return $this->selectMenu(3);*/
    }

    /** Funkcija koja otvara nalog novom adminu
     * @return function koja salje admina na preged svih admina
     */
    public function addAdmin()
    {
        $token = $this->getToken();
        if (is_null($token))
            return view('AdminBreachMessage',[]);

        $validation =  \Config\Services::validation();

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

        $user = new User([
            'email' => $emailNA,
            'password' => $passwordNA,
            'image' => null
        ]);
        $userModel = new UserModel();

        try {
            $userModel->insert($user);
        } catch(Exception $e) {
            $msg = 'Failed to insert the user into the database<br/>' . $e->getMessage();
            return view('Exception', ['msg' => $msg, 'destination' => '/AdminController/admins']);
        } 
   
        $adminModel = new AdminModel();
        $admin = new Admin([
            'email' => $emailNA,
            'firstName' => $fNameNA,
            'lastName' => $lNameNA
        ]);

        try {
            $adminModel->insert($admin);
        } catch(Exception $e) {
            throw new Exception('Failed to insert the user into the database ' . $e->getMessage());
        } 

        return $this->selectMenu(3);
    }
}