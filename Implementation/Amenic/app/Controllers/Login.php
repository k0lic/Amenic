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

use Exception;

use function App\Helpers\deleteCookie;
use function App\Helpers\generateToken;
use function App\Helpers\isValid;
use function App\Helpers\sendMail;
use function App\Helpers\setToken;

/** Login – Controller that controls user authentication / password reset
 * 
 * @version 1.0
 */

class Login extends BaseController {

    /**
     * Clears errors stored in cookies
     * @return void
     */

    public function clearErrors() {

        helper('auth');
        
        // Delete the error cookies
        deleteCookie('resetError');
        deleteCookie('loginError');
    }

    /**
     * Checks the validity of the reset token, and serves a reset page
     * @param User token
     * @return view
     */

    public function reset($token) {

        helper('auth');
        
        $ret = isValid($token);

        if(is_null($ret) || !$ret) {
            // Invalid reset link
            header('Location: /');
            exit();
        }

        return view('PasswordReset/passwordReset.php', ['token' => $token, 'errors' => []]);
    }

    /**
     * Gather form data and update the database
     * @return view
     */

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

                $userModel->where([
                    'email' => $ret->email
                    ])->set([
                    'password' => $password       
                    ])->update();
                
            } catch(Exception $e) {
                return view('PasswordReset/passwordResetFatal.php');
            }
        } else {
            return view('PasswordReset/passwordReset.php', ['token' => $_POST['token'], 'errors' => $validation->getErrors()]);
        }
        // Delete the login error cookie
        $this->clearErrors();

        
        return view('PasswordReset/passwordResetSuccess.php');
    }

    /**
     * Checks user credentials and sends a password reset email
     * @return void
     */

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
            <a href=\"".base_url()."/login/reset/$token\">Password reset</a>
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

    /**
     * The function that does the actual heavylifting - generates the token and logs the user in
     * @return void
     */

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

                if($user->approved == 0) {
                    throw new Exception("This account is not yet approved!");
                }

                if($user->closed == 1) {
                    $cinemaModel->where([
                        'email' => $email
                        ])->set([
                        'closed' => 0         
                        ])->update();
                }
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
            } else if($type == 'Worker') {
                $workerModel = new WorkerModel();

                $worker = $workerModel->find($user->email);

                $tokenPayload = [
                    'firstName' => $user->firstName,
                    'lastName' => $user->lastName,
                    'email' => $user->email,
                    'cinemaEmail' => $worker->idCinema,
                    'type' => $type
                ];
            } else {
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