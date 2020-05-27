<?php namespace App\Models;

use CodeIgniter\Model;

class ComingSoonModel extends Model
{
    protected $table = 'ComingSoon';
    protected $primaryKey= 'email';
    protected $returnType= 'App\Entities\ComingSoon';   
    protected $allowedFields= ['tmdbID','email'];
}
