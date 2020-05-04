<?php namespace App\Models;

use CodeIgniter\Model;

class Projection extends Model
{
    protected $table = 'Projections';
    protected $primaryKey= 'idPro';
    protected $returnType= 'object';   
    protected $allowedFields = ['roomName','email','dateTime','price','canceled','tmdbID','idTech'];     
}
