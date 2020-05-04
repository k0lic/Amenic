<?php namespace App\Models;

use CodeIgniter\Model;

class Technology extends Model
{
    protected $table = 'Technologies';
    protected $primaryKey= 'idTech';
    protected $returnType= 'object';
    protected $allowedFields = ['name'];    
}
