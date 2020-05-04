<?php namespace App\Models;

use CodeIgniter\Model;

class RoomTechnology extends Model
{
    #kompozit
    protected $table = 'RoomTechnologies';
    protected $primaryKey= 'email';
    protected $returnType= 'object';    
}
