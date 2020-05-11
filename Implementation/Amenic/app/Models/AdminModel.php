<?php namespace App\Models;

use App\Models\SmartDeleteModel;
use App\Models\UserModel;

class AdminModel extends SmartDeleteModel
{
    protected $table = 'Admins';
    protected $primaryKey= 'email';
    protected $returnType= 'App\Entities\Admin';    
    protected $allowedFields= ['email','firstName', 'lastName']; 

    #dodaj ostala pravila
    protected $validationRules = ['email' => 'required', 'firstName' => 'required', 'lastName' => 'required'];

    /*
    za modele sa kompozitnim primarnim kljucevima definisati:
    find(id) find([id1,id2,id3])

    $protected $beforeInsert | $beforeUpdate = [] - skup fja koje se pozivaju
    */

    // Deletes the user base object with the admin.
    public function smartDelete($email)
    {
        $usermdl = new UserModel();
        $this->delete($email);
        $usermdl->smartDelete($email);
    }

}
