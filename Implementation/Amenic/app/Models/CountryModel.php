<?php namespace App\Models;

use CodeIgniter\Model;

/**
 *  Model used for database operations focused on the 'Countries' table.
 * 
 *  @version 1.0
 */
class CountryModel extends Model
{
    /**
     *  @var string $table table name
     */
    protected $table = 'Countries';

    /**
     *  @var string $primaryKey primary key name
     */
    protected $primaryKey= 'idCountry';

    /**
     *  @var object $returnType the type of the return objects for methods of this class
     */
    protected $returnType= 'object';

    /**
     *  @var array $allowedFields fields in the table that can be manipulated using this model
     */
    protected $allowedFields = ['name'];
}
