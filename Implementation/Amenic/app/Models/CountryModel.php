<?php namespace App\Models;

use CodeIgniter\Model;

class CountryModel extends Model
{
    protected $table = 'Countries';
    protected $primaryKey= 'idCountry';
    protected $returnType= 'object';    
    protected $allowedFields = ['name'];
}