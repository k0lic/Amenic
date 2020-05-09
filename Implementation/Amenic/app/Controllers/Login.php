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

class Login extends BaseController {

    public function forgot() {
        echo "I forgot the password!";
    }

    public function index() {
        if(session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        helper(['form', 'url']);

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

            $_SESSION['user']['firstName'] = $user->firstName;
            $_SESSION['user']['lastName'] = $user->lastName;
            $_SESSION['user']['email'] = $user->email;
            $_SESSION['user']['type'] = $type;

            // Generate the JWT
            $tokenPayload = [
                'firstName' => $user->firstName,
                'lastName' => $user->lastName,
                'email' => $user->type,
                'type' => $user->firstName
            ];

            $key = '__drazenRocks__';

            $jwt = JWT::encode($tokenPayload, base64_decode(strtr($key, '-_', '+/')), 'HS256');

            $_SESSION['user']['token'] = $jwt;

            //$decoded = JWT::decode($jwt, base64_decode(strtr($key, '-_', '+/')), ['HS256']);

        } catch(Exception $e) {
            $_SESSION['loginErr'] = $e->getMessage();
            header('Location: /');
            exit();
        }

        $_SESSION['loginErr'] = '';

        header('Location: /');
        exit();
    }

}