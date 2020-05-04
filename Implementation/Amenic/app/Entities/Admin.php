<?php namespace App\Entities;

use CodeIgniter\Entity;

class Admin extends Entity
{
    /*
        fill metoda koja prima niz kljuc vrednost i postavlja polja u modelu, korisno da se koristi uz post
        automatski se poziva ova metoda i ako se ista stvar prosledi konstruktoru
    */

    /* 
    set/getImePolja() - poziva se svaki put kada se uradi $nesto = $admin->email; ili $admin->email = $nesto;
    */

    /*
    protected $casts = [ 'confirmed' => 'boolean' ] ?boolean dozvoljava i null
    moze da se kastuje i u array, json ili json-array
    $admin->hasChanged('email'); true/false ili za ceo entitet $user->hasChanged()

    ove dole fje ce biti korisne za hesiranje i neku drugu obradu podataka
    */

    public function setEmail(string $mail)
    {
        $this->attributes['email'] = $mail;

        return $this;
    }

    public function setFirstName(string $first)
    {
        $this->attributes['firstName'] = $first;

        return $this;
    }

    public function setLastName(string $last)
    {
        $this->attributes['lastName'] = $last;

        return $this;
    }

    public function getEmail(string $mail)
    {
        return $this->email;
    }

    public function getFirstName(string $first)
    {
        return $this->firstName;
    }

    public function getLastName(string $last)
    {
        return $this->lastName;
    }
}