<?php namespace App\Models;

/*
    Author: Andrija Kolić
    Github: k0lic
*/

use App\Models\SmartDeleteModel;
use App\Models\UserModel;
use App\Models\ReservationModel;

use function App\Helpers\sendMailOnReservationDelete;

/**
 *  Model used for database operations focused on the 'RUsers' table.
 *  Extends the SmartDeleteModel.
 * 
 *  @version 1.0
 */
class RUserModel extends SmartDeleteModel
{
    /**
     *  @var string $table table name
     */
    protected $table = 'RUsers';

    /**
     *  @var string $primaryKey primary key name
     */
    protected $primaryKey= 'email';

    /**
     *  @var object $returnType the type of the return objects for methods of this class
     */
    protected $returnType= 'App\Entities\RUser';

    /**
     *  @var array $allowedFields fields in the table that can be manipulated using this model
     */
    protected $allowedFields= ['email', 'firstName', 'lastName', 'phoneNumber', 'idCountry', 'idCity'];

    /**
     *  Deletes the user base object with the registered user, along with all of its reservations.
     * 
     *  @param string $email email of the chosen ruser account
     * 
     *  @return void
     */
    public function smartDelete($email)
    {
        $usermdl = new UserModel();
        $resmdl = new ReservationModel();
        $reservations = $resmdl->where("email", $email)->findAll();
        helper('mailer_helper');
        foreach ($reservations as $res)
        {
            if (!$res->confirmed)
            {
                sendMailOnReservationDelete($res);
            }
            $resmdl->smartDelete($res->idRes);
        }
        $this->delete($email);
        $usermdl->smartDelete($email);
    }
}
