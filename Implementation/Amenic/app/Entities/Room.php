<?php namespace App\Entities;

/*

    Author: Martin Mitrović
    Github: Rpsaman13000

*/

use CodeIgniter\Entity;

/** Room - object of this class represents one row in Rooms table
 *  @version 1.0
 */
class Room extends Entity
{
    /*
        Columns:
         -name
         -email
         -numberOfRows
         -seatsInRow 
    */    
   
    public function toString()
    {
        return "Room<br/><br/>name: ".$this->name."<br/>email: ".$this->email."<br/>numberOfRows: ".$this->numberOfRows."<br/>seatsInRow: ".$this->seatsInRow;
    }
}

