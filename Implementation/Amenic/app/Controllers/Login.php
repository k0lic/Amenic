<?php namespace App\Controllers;

/*
    Author: Miloš Živkovic
    Github: zivkovicmilos
*/

use \App\Models\UserModel;
use \App\Models\RUserModel;
use \App\Models\AdminModel;
use \App\Models\CinemaModel;

use \Firebase\JWT\JWT;

use Exception;

use function App\Helpers\deleteCookie;
use function App\Helpers\generateToken;
use function App\Helpers\isValid;
use function App\Helpers\setToken;

class Login extends BaseController {

    
    public function reset($token) {

        helper('auth');
        
        $ret = isValid($token);

        if(!$ret) {
           return view('index.php');
        }

        // Invalid reset link
        header('Location: /');
        exit();
    }

    public function forgot() {

        helper('auth');

        $formData = $_POST;
        $email = $formData['email'];

        try {

            $userModel = new UserModel();
            $user = $userModel->find($email);

            if(is_null($user)) {
                throw new Exception("No user with this email exists");
            }

            // Generate the token for the email
            $tokenPayload = [
                'email' => $user->email
            ];

            $token = generateToken($tokenPayload);
            
            // Email the link to the user
            $to = $email;
            $subject = 'Amenic - Password reset';
            $message = "Dear user, <br /> Someone has requested a password reset for your account. If this wasn't you, please ignore this message. <br /> To reset the password, please follow the link below: <br/>
            localhost:8080/login/reset/$token
            ";
            $from = 'noreply@amenic.com';

            if(!mail($to, $subject, $message)) {
                throw new Exception('Unable to send email, please try again');
            }

        } catch(Exception $e) {
            // Cookie expires in 5min.
            setcookie('resetError', $e->getMessage(), time()+300);
            $_COOKIE['resetError'] = $e->getMessage();

            header('Location: /');
            exit();
        }

        // Delete the reset error cookie
        deleteCookie('resetError');

        header('Location: /');
        exit();

    }

    public function index() {

        helper('auth');

        $formData = $_POST;

        $email = $formData['email'];
        $password = $formData['password'];

        $userModel = new UserModel();

        try {
            $user = $userModel->find($email);

            if(is_null($user)) {
                throw new Exception("No user with this email exists");
            }

            // Verify the password
            if(!password_verify($password, $user->password)) {
                throw new Exception("Incorrect password");
            }

            // Find out the account type
            $ruserModel = new RUserModel();
            $cinemaModel = new CinemaModel();
            $adminModel = new AdminModel();

            $type = '';

            if(!is_null($ruserModel->find($email))) {
                $user = $ruserModel->find($email);
                $type = 'RUser';
            } else if(!is_null($cinemaModel->find($email))) {
                $user = $cinemaModel->find($email);
                $type = 'Cinema';
            } else if(!is_null($adminModel->find($email))) {
                $user = $adminModel->find($email);
                $type = 'Admin';
            } else {
                throw new Exception('Internal server error');
            }

            // Generate the JWT
            $tokenPayload = [
                'firstName' => $user->firstName,
                'lastName' => $user->lastName,
                'email' => $user->email,
                'type' => $type
            ];

            $token = generateToken($tokenPayload);
            setToken($token);

        } catch(Exception $e) {

            // Cookie expires in 5min.
            setcookie('loginError', $e->getMessage(), time()+300);
            $_COOKIE['loginError'] = $e->getMessage();

            header('Location: /');
            exit();
        }

        // Delete the login error cookie
        deleteCookie('loginError');

        header('Location: /');
        exit();
    }

}