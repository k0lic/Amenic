<?php namespace App\Models;

use CodeIgniter\Model;

class GalleryModel extends Model
{
    protected $table = 'Galleries';
    protected $primaryKey= 'email';
    protected $returnType= 'App\Entities\Movie';
    protected $allowedFields = [
        'email', 'name', 'image'
    ];
}
