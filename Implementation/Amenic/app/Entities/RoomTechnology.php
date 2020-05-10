<?php namespace App\Entities;

use CodeIgniter\Entity;

class RoomTechnology extends Entity
{

    /*
        name
        email
        idTech
    */    
   
    public function toString()
    {
        return "RoomTechnology<br/><br/>name: ".$this->name."<br/>email: ".$this->email."<br/>idTech: ".$this->idTech;
    }
}

