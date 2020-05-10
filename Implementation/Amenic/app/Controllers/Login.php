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

use function App\Helpers\generateToken;
use function App\Helpers\setToken;

class Login extends BaseController {

    public function forgot() {
        echo "I forgot the password!";
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
        setcookie('loginError', null, time() - 3600, '/');
        if(isset($_COOKIE['loginError'])) {
            unset($_COOKIE['loginError']);
        }

        header('Location: /');
        exit();
    }

}