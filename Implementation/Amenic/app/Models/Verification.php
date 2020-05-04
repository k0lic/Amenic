<?php namespace App\Models;

use CodeIgniter\Model;

class Verification extends Model
{
    protected $table = 'Verifications';
    protected $primaryKey= 'email';
    protected $returnType= 'object';    
}
