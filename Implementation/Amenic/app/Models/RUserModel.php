<?php namespace App\Models;

use CodeIgniter\Model;

class RUserModel extends Model
{
    protected $table = 'RUsers';
    protected $primaryKey= 'email';
    protected $returnType= 'App\Entities\RUser';
    protected $allowedFields= ['email', 'firstName', 'lastName', 'phoneNumber', 'idCountry', 'idCity'];  

}
