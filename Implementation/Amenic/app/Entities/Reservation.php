<?php namespace App\Entities;

use CodeIgniter\Entity;

class Reservation extends Entity
{
    /*
        idRes
        confirmed
        idPro
        email
    */    
   
    protected $casts = [ 'confirmed' => 'boolean'];

}

