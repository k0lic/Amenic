<?php namespace App\Entities;

/*

    Author: Martin Mitrović
    Github: Rpsaman13000

*/

use CodeIgniter\Entity;

/** Worker - object of this class represents one row in Workers table
 *  @version 1.0
 */
class Worker extends Entity
{
    /*
        Columns:
         -email
         -idCinema
         -firstName
         -lastName
    */      
   
    public function toString()
    {
        return "Worker<br/><br/>email: ".$this->email."<br/>idCinema: ".$this->idCinema."<br/>firstName: ".$this->firstName."<br/>lastName: ".$this->lastName;
    }
}

