<?php namespace App\Models;

use CodeIgniter\Model;

class Room extends Model
{
    #opet kompozitni primarni kljuc
    protected $table = 'Rooms';
    protected $primaryKey= 'email';
    protected $returnType= 'object';    
}
