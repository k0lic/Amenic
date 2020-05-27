<?php namespace App\Entities;

/*

    Author: Martin Mitrović
    Github: Rpsaman13000

*/

use CodeIgniter\Entity;

/** Tecnology - object of this class represents one row in Technologies table
 *  @version 1.0
 */
class Technology extends Entity
{
    /*
        Columns:
         -idTech
         -name
    */    
   
    public function toString()
    {
        return "Technology<br/><br/>idTech: ".$this->idTech."<br/>name: ".$this->name;
    }
}

