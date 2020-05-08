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
    }

    private function isValid($step, $formData) {
        $formData = $_POST;
        

        // TODO
        return true;
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
    }

    public function user($step) {
        if(!$_SERVER["REQUEST_METHOD"] = "POST") {
            // Unauthorized GET request
            return $this->index();
        }

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
    }
}
