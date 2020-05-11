<?php namespace App\Models;

use App\Models\SmartDeleteModel;
use App\Models\UserModel;
use App\Models\ReservationModel;

class RUserModel extends SmartDeleteModel
{
    protected $table = 'RUsers';
    protected $primaryKey= 'email';
    protected $returnType= 'App\Entities\RUser';
    protected $allowedFields= ['email', 'firstName', 'lastName', 'phoneNumber', 'idCountry', 'idCity'];

    // Deletes the user base object with the registered user, along with all of its reservations.
    public function smartDelete($email)
    {
        $usermdl = new UserModel();
        $resmdl = new ReservationModel();
        $reservationIds = $resmdl->where("email", $email)->findColumn("idRes");
        foreach ($reservationIds as $id)
            $resmdl->delete($id);  //smartDeleteWithEmail($id); ======================================================== TODO
        $this->delete($email);
        $usermdl->smartDelete($email);
    }
}
