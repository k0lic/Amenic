<?php namespace App\Models;

use CodeIgniter\Model;

class Movie extends Model
{
    protected $table = 'Movies';
    protected $primaryKey= 'tmdbID';
    protected $returnType= 'object';    
}
