<?php namespace App\Models;

use CodeIgniter\Model;

/**
 *  Model used for database operations focused on the 'Galleries' table.
 * 
 *  @version 1.0
 */
class GalleryModel extends Model
{
    /**
     *  @var string $table table name
     */
    protected $table = 'Galleries';

    /**
     *  @var string $primaryKey primary key name
     */
    protected $primaryKey= 'email';

    /**
     *  @var object $returnType the type of the return objects for methods of this class
     */
    protected $returnType= 'App\Entities\Movie';

    /**
     *  @var array $allowedFields fields in the table that can be manipulated using this model
     */
    protected $allowedFields = [
        'email', 'name', 'image'
    ];
}
