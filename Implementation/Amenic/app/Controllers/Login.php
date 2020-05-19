<?php namespace App\Controllers;

/*
    Author: Miloš Živkovic
    Github: zivkovicmilos
*/

use \App\Models\UserModel;
use \App\Models\RUserModel;
use \App\Models\AdminModel;
use \App\Models\CinemaModel;
use \App\Models\WorkerModel;

use \Firebase\JWT\JWT;
use PHPMailer\PHPMailer\PHPMailer;

use Exception;

use function App\Helpers\deleteCookie;
use function App\Helpers\generateToken;
use function App\Helpers\isValid;
use function App\Helpers\sendMail;
use function App\Helpers\setToken;

class Login extends BaseController {

    public function clearErrors() {

        helper('auth');
        // Delete the error cookies
        deleteCookie('resetError');
        deleteCookie('loginError');

        header('Location: /');
        exit();
    }

    public function reset($token) {

        helper('auth');
        
        $ret = isValid($token);

        if(!is_null($ret) || $ret) {
           return view('PasswordReset/passwordReset.php', ['token' => $token, 'errors' => []]);
        }

        // Invalid reset link
        header('Location: /');
        exit();
    }

    public function handleReset() {
        if($_SERVER["REQUEST_METHOD"] != "POST") {
            // Unauthorized GET request
            header('Location: /register');
            exit();
        }

        // Extract form data
        $formData = [
            'firstPassword' => $_POST['firstPassword'],
            'secondPassword' => $_POST['secondPassword']
        ];

        // Load helpers
        helper(['form', 'url', 'auth']);
        $validation =  \Config\Services::validation();

        $ret = $validation->run($formData, "passwordCheck");

        if($ret == 1) {
            // Everything is ok, update the DB
            $ret = isValid($_POST['token']);
            try {
                // Hash the password with Bcrypt
                $password = password_hash($formData['firstPassword'], PASSWORD_BCRYPT, ['cost' => 8]); 

                // Save to the DB

                $userModel = new UserModel();
                //$user = $userModel->find($ret->email);

                $userModel->where([
                    'email' => $ret->email
                    ])->set([
                    'password' => $password       
                    ])->update();

                //$user->password = $password;
                //$userModel->save($user);
                
            } catch(Exception $e) {
                return view('PasswordReset/passwordResetFatal.php');
            }
        } else {
            return view('PasswordReset/passwordReset.php', ['token' => $_POST['token'], 'errors' => $validation->getErrors()]);
        }
        // Delete the login error cookie
        $this->clearErrors();

        return view ('PasswordReset/passwordResetSuccess.php');
    }

    public function forgot() {

        helper(['auth', 'mailer']);

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
            $subject = 'Amenic - Password reset';
            $message = "Dear user, <br /> Someone has requested a password reset for your account. If this wasn't you, please ignore this message. <br /> <br />To reset the password, follow the link below: <br/>
            <a href=\"http://localhost:8080/login/reset/$token\">Password reset</a>
            ";

            if(!sendMail($email, $subject, $message)) {
                throw new Exception("Mail count not be sent");
            } 

        } catch(Exception $e) {
            // Cookie expires in 5min.
            setcookie('resetError', $e->getMessage(), time()+300, '/');
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
            $workerModel = new WorkerModel();

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
            } else if(!is_null($workerModel->find($email))) {
                $user = $workerModel->find($email);
                $type = 'Worker';
            } else {
                throw new Exception('Internal server error');
            }

            // Generate the JWT

            $tokenPayload = null;

            if($type == 'Cinema') {
                $tokenPayload = [
                    'name' => $user->name,
                    'address' => $user->address,
                    'email' => $user->email,
                    'phone' => $user->phoneNumber,
                    'city' => $user->idCity,
                    'country' => $user->idCountry,
                    'type' => $type
                ];
            } else if($type == 'RUser') {
                $tokenPayload = [
                    'firstName' => $user->firstName,
                    'lastName' => $user->lastName,
                    'email' => $user->email,
                    'phone' => $user->phoneNumber,
                    'city' => $user->idCity,
                    'country' => $user->idCountry,
                    'type' => $type
                ];
            }else {
                $tokenPayload = [
                    'firstName' => $user->firstName,
                    'lastName' => $user->lastName,
                    'email' => $user->email,
                    'type' => $type
                ];
            }

            $token = generateToken($tokenPayload);
            setToken($token);

        } catch(Exception $e) {

            // Cookie expires in 5min.
            setcookie('loginError', $e->getMessage(), time()+300, '/');
            $_COOKIE['loginError'] = $e->getMessage();

            header('Location: /');
            exit();
        }

        // Delete the login error cookie
        $this->clearErrors();

        header('Location: /');
        exit();
    }

}