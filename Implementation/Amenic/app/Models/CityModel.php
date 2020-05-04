<?php namespace App\Models;

use CodeIgniter\Model;

class CityModel extends Model
{
    protected $table = 'Cities';
    protected $primaryKey= 'idCity';
    protected $returnType= 'object'; 
    protected $allowedFields = ['name','idCountry'];
}
