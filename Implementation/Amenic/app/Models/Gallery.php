<?php namespace App\Models;

use CodeIgniter\Model;

class Gallery extends Model
{
    #I ovde je primarni kljuc problem
    protected $table = 'Galleries';
    protected $primaryKey= 'email';
    protected $returnType= 'object';    
}
