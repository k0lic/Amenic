<?php namespace App\Entities;

use CodeIgniter\Entity;

class Room extends Entity
{

    /*
        name
        email
        numberOfRows
        seatsInRow
    */    
   
    public function toString()
    {
        return "Room<br/><br/>name: ".$this->name."<br/>email: ".$this->email."<br/>numberOfRows: ".$this->numberOfRows."<br/>seatsInRow: ".$this->seatsInRow;
    }
}

