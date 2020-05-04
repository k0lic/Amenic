<?php namespace App\Entities;

use CodeIgniter\Entity;

class Cinema extends Entity
{

    /*
        email
        name
        address
        phoneNumber
        description
        mngFirstName
        mngLastName
        mngPhoneNumber
        mngEmail
        banner 	
        approved	
        closed
        idCountryIndex
        idCityIndex 
    */

    protected $casts = [ 'approved' => 'boolean', 'closed' => 'boolean'];
    
    public function setNumber($numberString)
    {
        //fja koja proverava da li je broj dobro unet tj da li su samo brojevi u stringu
        $this->number = $numberString;

        return $this;
    }
}

