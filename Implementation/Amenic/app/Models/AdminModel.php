<?php namespace App\Models;

/*
    Author: Andrija Kolić
    Github: k0lic
*/

use App\Models\SmartDeleteModel;
use App\Models\UserModel;

/**
 *  Model used for database operations focused on the 'Admins' table.
 *  Extends the SmartDeleteModel.
 * 
 *  @version 1.0
 */
class AdminModel extends SmartDeleteModel
{
    /**
     *  @var string $table table name
     */
    protected $table = 'Admins';

    /**
     *  @var string $primaryKey primary key name
     */
    protected $primaryKey= 'email';

    /**
     *  @var object $returnType the type of the return objects for methods of this class
     */
    protected $returnType= 'App\Entities\Admin';

    /**
     *  @var array $allowedFields fields in the table that can be manipulated using this model
     */
    protected $allowedFields= ['email','firstName', 'lastName'];

    /**
     *  @var array $validationRules validation rules
     */
    protected $validationRules = ['email' => 'required', 'firstName' => 'required', 'lastName' => 'required'];

    /**
     *  Deletes the user base entity with the admin.
     * 
     *  @param string $email email address of the admin account to be deleted
     * 
     *  @return void
     */
    public function smartDelete($email)
    {
        $usermdl = new UserModel();
        $this->delete($email);
        $usermdl->smartDelete($email);
    }

}
