<?php namespace App\Models;

use CodeIgniter\Model;

class Worker extends Model
{
    protected $table = 'Workers';
    protected $primaryKey= 'email';
    protected $returnType= 'object';    
}
