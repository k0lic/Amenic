<?php namespace App\Models;

use CodeIgniter\Model;

/**
 *  Model used for database operations focused on the 'Technologies' table.
 * 
 *  @version 1.0
 */
class TechnologyModel extends Model
{
    /**
     *  @var string $table table name
     */
    protected $table = 'Technologies';

    /**
     *  @var string $primaryKey primary key name
     */
    protected $primaryKey= 'idTech';

    /**
     *  @var object $returnType the type of the return objects for methods of this class
     */
    protected $returnType= 'App\Entities\Technology';

    /**
     *  @var array $allowedFields fields in the table that can be manipulated using this model
     */
    protected $allowedFields = ['name'];    
}
