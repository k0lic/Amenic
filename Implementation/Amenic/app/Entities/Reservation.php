<?php namespace App\Entities;

/*

    Author: Martin Mitrović
    Github: Rpsaman13000

*/

use CodeIgniter\Entity;

/** Reservation - object of this class represents one row in Reservations table
 *  @version 1.0
 */
class Reservation extends Entity
{
    /*
        Columns:
         -idRes
         -confirmed
         -idPro
         -email
    */    
   
    //casting tinyint to boolean
    protected $casts = [ 'confirmed' => 'boolean'];
}

