<?php namespace App\Models;

use CodeIgniter\Model;

class Seat extends Model
{
    #kompozit
    protected $table = 'Seats';
    protected $primaryKey= 'email';
    protected $returnType= 'object';    
}
