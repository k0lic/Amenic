<?php namespace App\Models;

use CodeIgniter\Model;

class SeatModel extends Model
{
    #kompozit
    #find($id)–findAll()–findAll($limit, $offset)–first()–where($name, $value)–insert($data)–update($id, $data)–save($data)–delete($id)
    protected $table = 'Seats';
    protected $primaryKey= 'email';
    protected $returnType= 'object';    
}
