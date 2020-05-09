<?php namespace App\Models;

use CodeIgniter\Model;

class TechnologyModel extends Model
{
    protected $table = 'Technologies';
    protected $primaryKey= 'idTech';
    protected $returnType= 'App\Entities\Technology';
    protected $allowedFields = ['name'];    
}
