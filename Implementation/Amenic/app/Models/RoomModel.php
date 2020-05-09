<?php namespace App\Models;

use CodeIgniter\Model;

class RoomModel extends Model
{
    #opet kompozitni primarni kljuc
    #find($id)–findAll()–findAll($limit, $offset)–first()–where($name, $value)–insert($data)–update($id, $data)–save($data)–delete($id)
    protected $table = 'Rooms';
    protected $primaryKey= 'email';
    protected $returnType= 'App\Entities\Movie';
}
