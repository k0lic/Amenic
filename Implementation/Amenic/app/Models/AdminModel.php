<?php namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends Model
{
    protected $table = 'Admins';
    protected $primaryKey= 'email';
    protected $returnType= 'App\Entities\Admin';    

    #dodaj ostala pravila
    protected $validationRules = ['email' => 'required', 'firstName' => 'required', 'lastName' => 'required'];

    /*
    za modele sa kompozitnim primarnim kljucevima definisati:
    find(id) find([id1,id2,id3])

    $protected $beforeInsert | $beforeUpdate = [] - skup fja koje se pozivaju
    */
}
