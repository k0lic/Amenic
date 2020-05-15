<?php namespace App\Controllers;

use \App\Models\UserModel;
use \App\Models\WorkerModel;
use App\Libraries\Upload;

use function App\Helpers\isAuthenticated;
use function App\Helpers\isValid;
use function App\Helpers\generateToken;
use function App\Helpers\setToken;

class WorkerController extends BaseController
{
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
            $db->transCommit();
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
}