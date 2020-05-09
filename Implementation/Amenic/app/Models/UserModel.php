<?php namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'Users';
    protected $primaryKey= 'email';
    protected $returnType= 'App\Entities\User';    
    protected $allowedFields = ['email','password','image']; 
}
