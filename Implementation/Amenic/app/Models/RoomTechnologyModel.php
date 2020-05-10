<?php namespace App\Models;

use CodeIgniter\Model;

class RoomTechnologyModel extends Model
{
    #kompozit
    #find($id)–findAll()–findAll($limit, $offset)–first()–where($name, $value)–insert($data)–update($id, $data)–save($data)–delete($id)
    protected $table = 'RoomTechnologies';
    protected $primaryKey= 'email';
    protected $returnType= 'App\Entities\RoomTechnology';
    protected $allowedFields = ['name','email','idTech'];
     
}
