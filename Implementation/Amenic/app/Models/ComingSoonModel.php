<?php namespace App\Models;

use CodeIgniter\Model;

#poseduje kompozitni kljuc
class ComingSoonModel extends Model
{
    #find($id)–findAll()–findAll($limit, $offset)–first()–where($name, $value)–insert($data)–update($id, $data)–save($data)–delete($id)
    protected $table = 'ComingSoon';
    protected $primaryKey= 'email';
    protected $returnType= 'object';    
}
