<?php namespace App\Models;

use App\Models\SmartDeleteModel;
use App\Models\UserModel;

class AdminModel extends SmartDeleteModel
{
    protected $table = 'Admins';
    protected $primaryKey= 'email';
    protected $returnType= 'App\Entities\Admin';    
    protected $allowedFields= ['email','firstName', 'lastName']; 
    protected $validationRules = ['email' => 'required', 'firstName' => 'required', 'lastName' => 'required'];

    // Deletes the user base object with the admin.
    public function smartDelete($email)
    {
        $usermdl = new UserModel();
        $this->delete($email);
        $usermdl->smartDelete($email);
    }

}
