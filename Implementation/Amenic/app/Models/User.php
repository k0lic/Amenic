<?php namespace App\Models;

use CodeIgniter\Model;

class User extends Model
{
    protected $table = 'Users';
    protected $primaryKey= 'email';
    protected $returnType= 'object';    
}
