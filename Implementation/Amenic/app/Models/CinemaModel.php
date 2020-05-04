<?php namespace App\Models;

use CodeIgniter\Model;

class CinemaModel extends Model
{
    protected $table = 'Cinemas';
    protected $primaryKey= 'email';
    protected $returnType= 'App\Entities\Cinema';    
}