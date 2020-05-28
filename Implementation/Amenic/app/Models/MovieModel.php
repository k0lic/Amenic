<?php namespace App\Models;

use CodeIgniter\Model;

/**
 *  Model used for database operations focused on the 'Movies' table.
 * 
 *  @version 1.0
 */
class MovieModel extends Model
{
    /**
     *  @var string $table table name
     */
    protected $table = 'Movies';

    /**
     *  @var string $primaryKey primary key name
     */
    protected $primaryKey= 'tmdbID';

    /**
     *  @var object $returnType the type of the return objects for methods of this class
     */
    protected $returnType= 'App\Entities\Movie';

    /**
     *  @var array $allowedFields fields in the table that can be manipulated using this model
     */
    protected $allowedFields = [
        'tmdbID', 'title', 'released', 'runtime', 'genre', 'director', 'writer', 'actors',
        'plot', 'poster', 'backgroundImg', 'imdbRating', 'imdbID', 'reviews', 'trailer'
    ];

}
