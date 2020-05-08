<?php namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'Users';
    protected $primaryKey= 'email';
    protected $returnType= 'object';    
    protected $allowedFields = ['email','password','image']; 
}
