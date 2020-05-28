<?php namespace App\Models;

use App\Models\SmartDeleteModel;
use App\Models\VerificationModel;

/**
 *  Model used for database operations focused on the 'Users' table.
 *  Extends the SmartDeleteModel.
 * 
 *  @version 1.0
 */
class UserModel extends SmartDeleteModel
{
    /**
     *  @var string $table table name
     */
    protected $table = 'Users';

    /**
     *  @var string $primaryKey primary key name
     */
    protected $primaryKey= 'email';

    /**
     *  @var object $returnType the type of the return objects for methods of this class
     */
    protected $returnType= 'App\Entities\User';

    /**
     *  @var array $allowedFields fields in the table that can be manipulated using this model
     */
    protected $allowedFields = ['email','password','image'];

    /**
     *  /USED TO/ Deletes the verifaction entry with the user.
     *  Called by inherited objects.
     * 
     *  @param string $email email of the chosen user account
     * 
     *  @return void
     */
    public function smartDelete($email)
    {
        $this->delete($email);
    }
}
