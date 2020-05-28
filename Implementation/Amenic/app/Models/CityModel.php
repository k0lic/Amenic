<?php namespace App\Models;

use CodeIgniter\Model;

/**
 *  Model used for database operations focused on the 'Cities' table.
 * 
 *  @version 1.0
 */
class CityModel extends Model
{
    /**
     *  @var string $table table name
     */
    protected $table = 'Cities';

    /**
     *  @var string $primaryKey primary key name
     */
    protected $primaryKey= 'idCity';

    /**
     *  @var object $returnType the type of the return objects for methods of this class
     */
    protected $returnType= 'object'; 

    /**
     *  @var array $allowedFields fields in the table that can be manipulated using this model
     */
    protected $allowedFields = ['name','idCountry'];
}
