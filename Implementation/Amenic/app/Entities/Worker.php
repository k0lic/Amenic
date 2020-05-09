<?php namespace App\Entities;

use CodeIgniter\Entity;

class Worker extends Entity
{

    /*
        email
        idCinema
        firstName
        lastName
    */    
   
    public function toString()
    {
        return "Worker<br/><br/>email: ".$this->email."<br/>idCinema: ".$this->idCinema."<br/>firstName: ".$this->firstName."<br/>lastName: ".$this->lastName;
    }
}

