<?php namespace App\Models;

use CodeIgniter\Model;

class RoomTechnologyModel extends Model
{
    protected $table = 'RoomTechnologies';
    protected $primaryKey= 'email';
    protected $returnType= 'App\Entities\RoomTechnology';
    protected $allowedFields = ['name','email','idTech'];
     
}
