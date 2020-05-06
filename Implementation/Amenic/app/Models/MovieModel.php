<?php namespace App\Models;

use CodeIgniter\Model;


class MovieModel extends Model
{
    protected $table = 'Movies';
    protected $primaryKey= 'tmdbID';
    protected $returnType= 'App\Entities\Movie';    
    protected $allowedFields = [
        'tmdbID', 'title', 'released', 'runtime', 'genre', 'director', 'writer', 'actors',
        'plot', 'poster', 'backgroundImg', 'imdbRating', 'imdbID', 'reviews', 'trailer'
    ];
}
