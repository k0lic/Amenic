<?php namespace App\Models;

use CodeIgniter\Model;

/**
 *  Model used for database operations focused on the 'Seats' table.
 * 
 *  @version 1.0
 */
class SeatModel extends Model
{
    /**
     *  @var string $table table name
     */
    protected $table = 'Seats';

    /**
     *  @var string $primaryKey primary key name
     */
    protected $primaryKey= 'email';

    /**
     *  @var object $returnType the type of the return objects for methods of this class
     */
    protected $returnType= 'App\Entities\Seat';

    /**
     *  @var array $allowedFields fields in the table that can be manipulated using this model
     */
    protected $allowedFields= ['idPro', 'rowNumber', 'seatNumber', 'status', 'idRes'];
}
