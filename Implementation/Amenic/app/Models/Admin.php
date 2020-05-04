<?php namespace App\Models;

use CodeIgniter\Model;

class Admin extends Model
{
    protected $table = 'Admins';
    protected $primaryKey= 'email';
    protected $returnType= 'object';    
}
