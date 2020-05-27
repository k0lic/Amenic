<?php namespace App\Entities;

/*

    Author: Martin Mitrović
    Github: Rpsaman13000

*/

use CodeIgniter\Entity;

/** Cinema - object of this class represents one row in Cinemas table
 *  @version 1.0
 */
class Cinema extends Entity
{
    /*
        Columns:
         -email
         -name
         -address
         -phoneNumber
         -description
         -mngFirstName
         -mngLastName
         -mngPhoneNumber
         -mngEmail
         -banner 	
         -approved	
         -closed
         -idCountryIndex
         -idCityIndex 
    */  

    //converting from tinyint to boolean
    protected $casts = [ 'approved' => 'boolean', 'closed' => 'boolean'];
}

