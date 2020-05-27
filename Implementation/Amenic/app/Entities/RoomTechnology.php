<?php namespace App\Entities;

/*

    Author: Martin Mitrović
    Github: Rpsaman13000

*/

use CodeIgniter\Entity;

/** RoomTecnology - object of this class represents one row in RoomTechnologies table
 *  @version 1.0
 */
class RoomTechnology extends Entity
{
    /*
        Columns:
         -name
         -email
         -idTech
    */    
   
    public function toString()
    {
        return "RoomTechnology<br/><br/>name: ".$this->name."<br/>email: ".$this->email."<br/>idTech: ".$this->idTech;
    }
}

