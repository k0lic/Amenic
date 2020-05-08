<?php namespace App\Controllers;

/*
    Author: Miloš Živkovic
    Github: zivkovicmilos
*/

class Register extends BaseController {

    private function clearSessionData() {
        // Start the registration forms on a clean slate
        for($i =0; $i < 3; $i++) {
            if(!isset($_SESSION["step"][$i])) {
                unset($_SESSION["step"][$i]);
            }
        }

        if(!isset($_SESSION["errors"])) {
            unset($_SESSION["errors"]);
        }
    }

    private function isValid($step, $data) {
        
        // Load helpers
        helper(['form', 'url']);
        $validation =  \Config\Services::validation();

        // Nothing to validate on section choice
        if($step == 1) return true;

        $type = ucfirst($_SESSION["step"][1]["type"]);

        return $validation->run($data, "p$step$type"); 
    }

	public function index() {	
        // Start the user session where form data will be stored
        session_start();

        $this->clearSessionData();

		return view('Registration/register',[]);
    }

    public function cinema($step) {
        if(!$_SERVER["REQUEST_METHOD"] = "POST") {
            // Unauthorized GET request
            return $this->index();
        }

	    session_start();
        if($this->isValid($step, $_POST)) {
            // Update the user fields
            $_SESSION["step"][$step] = $_POST;

            if($step == 4) {
                // Registration finished, save to the Database, 
                // and serve a success message
                echo "You made it :)";
                return view("Registration/Cinema/cinemaSuccess");
            }

            $nextPage = $step+1;
            return view("Registration/Cinema/cinemaP$nextPage");
        } else {
            // Return with errors
            echo "you fucked up";
        }

        
    }

    public function user($step) {
        if(!$_SERVER["REQUEST_METHOD"] = "POST") {
            // Unauthorized GET request
            return $this->index();
        }

	    session_start();
        if($this->isValid($step, $_POST)) {
            // Update the user fields
            $_SESSION["step"][$step] = $_POST;

            if($step == 3) {
                // Registration finished, save to the Database, 
                // and serve a success message
                echo "You made it :)";
                return view("Registration/Cinema/cinemaSuccess");
            }

            $nextPage = $step+1;
            return view("Registration/User/userP$nextPage");
        } else {
            // Return with errors
            echo "you fucked up";
        }
    }
}
