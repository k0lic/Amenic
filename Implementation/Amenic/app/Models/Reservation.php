<?php namespace App\Models;

use CodeIgniter\Model;

class Reservation extends Model
{
    protected $table = 'Reservations';
    protected $primaryKey= 'idRes';
    protected $returnType= 'object';  
    protected $allowedFields = ['confirmed','idPro','email'];  
}
