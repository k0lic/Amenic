<?php namespace App\Models;

use CodeIgniter\Model;

class TechnologyModel extends Model
{
    protected $table = 'Technologies';
    protected $primaryKey= 'idTech';
    protected $returnType= 'object';
    protected $allowedFields = ['name'];    
}
