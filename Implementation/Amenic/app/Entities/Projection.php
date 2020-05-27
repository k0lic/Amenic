<?php namespace App\Entities;

/*

    Author: Martin Mitrović
    Github: Rpsaman13000

*/

use CodeIgniter\Entity;

/** Projection - object of this class represents one row in Projections table
 *  @version 1.0
 */
class Projection extends Entity
{
    /*
        Columns:
         -idPro
         -roomName
         -email
         -dateTime
         -price
         -canceled
         -tmdbID
         -idTech
    */ 
   
    public function toString()
    {
        return "Projection<br/><br/>idPro: ".$this->idPro."<br/>roomName: ".$this->roomName."<br/>email: ".$this->email."<br/>dateTime: ".$this->dateTime.
        "<br/>price: ".$this->price."<br/>canceled: ".$this->canceled."<br/>tmdbID: ".$this->tmdbID."<br/>idTech: ".$this->idTech;
    }
}

