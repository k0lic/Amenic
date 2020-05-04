<?php namespace App\Models;

use CodeIgniter\Model;

class Cinema extends Model
{
    protected $table = 'Cinemas';
    protected $primaryKey= 'email';
    protected $returnType= 'object';    
}
