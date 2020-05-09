<?php namespace App\Entities;

use CodeIgniter\Entity;

class Technology extends Entity
{

    /*
        idTech
        name
    */    
   
    public function toString()
    {
        return "Technology<br/><br/>idTech: ".$this->idTech."<br/>name: ".$this->name;
    }
}

