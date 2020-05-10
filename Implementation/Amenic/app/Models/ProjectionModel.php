<?php namespace App\Models;

/*
    Author: Andrija Kolić
    Github: k0lic
*/

use CodeIgniter\Model;

class ProjectionModel extends Model
{
    protected $table = 'Projections';
    protected $primaryKey= 'idPro';
    protected $returnType= 'App\Entities\Projection';   
    protected $allowedFields = ['idPro','roomName','email','dateTime','price','canceled','tmdbID','idTech'];     

    public function findAllProjectionsOfMyCinema($cinemaEmail)
    {
        $projections = $this->where('email',$cinemaEmail)->orderBy('dateTime','ASC')->findAll();
        return $projections;
    }
}

?>