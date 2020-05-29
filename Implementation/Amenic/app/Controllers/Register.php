<?php namespace App\Controllers;

/*
    Author: Miloš Živkovic
    Github: zivkovicmilos
*/

use \App\Models\CountryModel;
use \App\Models\CityModel;
use \App\Models\CinemaModel;
use \App\Models\RUserModel;
use \App\Models\UserModel;
use \App\Entities\Cinema;
use \App\Entities\RUser;
use \App\Entities\User;
use Exception;

use function App\Helpers\isAuthenticated;
use function App\Helpers\isValid;

/** Register – Controller that handles user registration
 * 
 * @version 1.0
 */

class Register extends BaseController {

    /**
     * Clears PHP Session data
     * @return void
     */

    private function clearSessionData() {
        // Start the registration forms on a clean slate
        for($i =0; $i < 3; $i++) {
            if(!isset($_SESSION["step"][$i])) {
                unset($_SESSION["step"][$i]);
            }
        }
    }

    /**
     * Checks if form input for a specific step is valid
     * @param Number step
     * @param Array data
     * @return view
     */

    private function isValid($step, $data) {
        
        // Load helpers
        helper(['form', 'url']);
        $validation =  \Config\Services::validation();

        // Nothing to validate on section choice
        if($step == 1) return true;

        $type = ucfirst($_SESSION["step"][1]["type"]);
       

        // Returns 1 if OK, otherwise it returns an array of errors
        if(($step == 3 && $type == "User") || ($step == 4 && $type == "Cinema" )) {
            $ret = $validation->run($data, "passwordCheck");
        } else {
            $ret = $validation->run($data, "p$step$type");
        }

        if($ret == 1) return 1;

        return $validation->getErrors();
    }


    /**
     * Initiates the register session
     * @return view
     */

	public function index() {	
        // Start the user session where form data will be stored
        $token = $this->getToken();

        if (!is_null($token)) {
            header('Location: /');
            exit();
        }

        session_start();

        $this->clearSessionData();

		return view('Registration/register',[]);
    }

    /**
     * Serves the cinema registration views
     * @param Number step
     * @return view
     */

    public function cinema($step) {

        return $this->next($step, "Cinema", $_POST);     
    }

    /**
     * Serves the user registration views
     * @param Number step
     * @return view
     */

    public function user($step) {

        return $this->next($step, "User", $_POST);
    }

    /**
     * Serves the next step in the registration process
     * @param Number step
     * @param String type
     * @param Array postData
     * @return view
     */

    public function next($step, $type, $postData) {
        $token = $this->getToken();

        if (!is_null($token)) {
            header('Location: /');
            exit();
        }

        session_start();
        
        $validationResult = $this->isValid($step, $postData);
        if($validationResult == 1) {
            // Update the user fields
            $_SESSION["step"][$step] = $postData;

            $type = ucfirst($_SESSION["step"][1]["type"]);

            if(($step == 3 && $type == "User") || ($step == 4 && $type == "Cinema" )) {
                // Registration finished, save to the Database, 
                // and serve a success message

                try {
                    if($type == "User") {
                        $this->registerUser();
                    } else {
                        $this->registerCinema();
                    }
                } catch(Exception $e) {
                    return view("Registration/registerError", ['msg' => $e->getMessage()]);
                }
                
                session_destroy();

                return view("Registration/$type/".strtolower($type)."Success");
            }

            $nextPage = $step+1;
            return view("Registration/$type/".strtolower($type)."P$nextPage");
        } else {
            // Return with errors
            return view("Registration/$type/".strtolower($type)."P$step", ['errors' => $validationResult]);
        }
    }

    // DB Functions //

    /**
     * Adds the user to the database
     * @return void
     * @throws Exception e
     */

    private function registerUser() {

        $step2Data = $_SESSION["step"][2];
        $step3Data = $_SESSION["step"][3];

        // Extract user data
        $firstName = ucfirst(strtolower($step2Data['firstName'])); 
        $lastName = ucfirst(strtolower($step2Data['lastName'])); 
        $email = strtolower($step2Data['email']); 
        $phone = isset($step2Data['phone'])?$step2Data['phone']:""; 
        $country = $step2Data['country']; 
        $city = $step2Data['city']; 

        // Hash the password with Bcrypt
        $password = password_hash($step3Data['firstPassword'], PASSWORD_BCRYPT, ['cost' => 8]); 

        $user = new User([
            'email' => $email,
            'password' => $password,
            'image' => null
        ]);

        $userModel = new UserModel();

        if(!is_null($userModel->find($email))) {
            throw new Exception("A user with the email '$email' is already registered!");
        }

        try {
            $userModel->insert($user);
        } catch(Exception $e) {
            throw new Exception('Failed to insert the user into the database ' . $e->getMessage());
        }

        $ruser = new RUser([
            'email' => $email,
            'firstName' => $firstName,
            'lastName' => $lastName,
            'phoneNumber' => $phone,
            'idCountry' => $country,
            'idCity' => $city
        ]);

        $ruserModel = new RUserModel();

        if(!is_null($ruserModel->find($email))) {
            $userModel->delete($email); // Roll back the changes
            throw new Exception("A RUser with the email '$email' is already registered!");
        }

        if($ruserModel->insert($ruser)) {
            throw new Exception('Failed to insert the reg. user into the database');
        }
    }

    /**
     * Adds the cinema to the database
     * @return void
     * @throws Exception e
     */

    private function registerCinema() {

        $step2Data = $_SESSION["step"][2];
        $step3Data = $_SESSION["step"][3];
        $step4Data = $_SESSION["step"][4];

        // Extract cinema data
        $cinemaName = $step2Data['cinemaName']; 
        $address = $step2Data['address']; 
        $phoneNumber = $step2Data['phoneNumber']; 
        $country = $step2Data['country']; 
        $city = $step2Data['city'];

        $description = isset($step2Data['description'])?$step2Data['description']:"";

        // Extract owner data
        $mngFirstName = ucfirst(strtolower($step3Data['mngFirstName'])); 
        $mngLastName = ucfirst(strtolower($step3Data['mngLastName'])); 
        $mngEmail = strtolower($step3Data['mngEmail']);
        $mngPhoneNumber = $step3Data['mngPhoneNumber'];

        // Hash the password
        $password = password_hash($step4Data['firstPassword'], PASSWORD_BCRYPT, ['cost' => 8]);

        $user = new User([
            'email' => $mngEmail,
            'password' => $password,
            'image' => null
        ]);

        $userModel = new UserModel();

        if(!is_null($userModel->find($mngEmail))) {
            throw new Exception("A cinema with the email '$mngEmail' is already registered!");
        }

        try {
            $userModel->insert($user);
        } catch(Exception $e) {
            throw new Exception('Failed to insert the cinema (user) into the database ' . $e->getMessage());
        }
        
        $cinema = new Cinema([
            'email' => $mngEmail,
            'name' => $cinemaName,
            'address' => $address,
            'phoneNumber' => $phoneNumber,
            'description' => $description,
            'mngFirstName' => $mngFirstName,
            'mngLastName' => $mngLastName,
            'mngPhoneNumber' => $mngPhoneNumber,
            'mngEmail' => $mngEmail,
            'banner' => null,
            'approved' => 0,
            'closed' => 0,
            'idCountry' => $country,
            'idCity' => $city
        ]);

        $cinemaModel = new CinemaModel();

        try {
            $cinemaModel->insert($cinema);
        } catch(Exception $e) {
            throw new Exception('Failed to insert the cinema into the database ' . $e->getMessage());
        }   
    }

    /** 
     * Gets the cookie containing basic info of a logged in user
     * @return object
     */

    private function getToken() {
        helper('auth');

        if (isset($_COOKIE['token'])) {
            $tokenCookie = $_COOKIE['token'];   
            $token = isValid($tokenCookie);
            
            if ($token && isAuthenticated("RUser")) {
                $image = (new UserModel())->find($token->email);
                $image = $image->image;
        
                $token->image = $image;
                return $token;
            }
        }
        return null;
    }

    public function getCountries() {
        $countryModel = new CountryModel();
        $countries = $countryModel->findAll();
        
        echo json_encode($countries);
    }

    public function getCities() {
        $cityModel = new CityModel();

        $countryId = $_REQUEST['idCountry'];

        $cities = $cityModel
                            ->where('idCountry', $countryId)
                            ->findAll();
    
        echo json_encode($cities);
    }

}
