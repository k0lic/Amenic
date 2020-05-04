<?php namespace App\Models;

use CodeIgniter\Model;

class GalleryModel extends Model
{
    #I ovde je primarni kljuc problem
    #find($id)–findAll()–findAll($limit, $offset)–first()–where($name, $value)–insert($data)–update($id, $data)–save($data)–delete($id)
    protected $table = 'Galleries';
    protected $primaryKey= 'email';
    protected $returnType= 'object';    
}
