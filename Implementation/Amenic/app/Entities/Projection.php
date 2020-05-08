<?php namespace App\Entities;

use CodeIgniter\Entity;

class Projection extends Entity
{

    /*
        idPro
        roomName
        email
        dateTime
        price
        canceled
        tmdbID
        idTech
    */    
   
    public function toString()
    {
        return "Projection<br/><br/>idPro: ".$this->idPro."<br/>roomName: ".$this->roomName."<br/>email: ".$this->email."<br/>dateTime: ".$this->dateTime.
        "<br/>price: ".$this->price."<br/>canceled: ".$this->canceled."<br/>tmdbID: ".$this->tmdbID."<br/>idTech: ".$this->idTech;
    }
}

