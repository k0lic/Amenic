<?php namespace App\Models;

use App\Models\SmartDeleteModel;
use App\Models\VerificationModel;

class UserModel extends SmartDeleteModel
{
    protected $table = 'Users';
    protected $primaryKey= 'email';
    protected $returnType= 'App\Entities\User';    
    protected $allowedFields = ['email','password','image'];

    // Deletes the verifaction entry with the user.
    public function smartDelete($email)
    {
        $this->delete($email);
    }
}
