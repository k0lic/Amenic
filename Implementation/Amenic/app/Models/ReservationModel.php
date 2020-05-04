<?php namespace App\Models;

use CodeIgniter\Model;

class ReservationModel extends Model
{
    protected $table = 'Reservations';
    protected $primaryKey= 'idRes';
    protected $returnType= 'App\Entities\Reservation';  
    protected $allowedFields = ['confirmed','idPro','email'];  
}
