<?php namespace App\Models;

use CodeIgniter\Model;

class SeatModel extends Model
{
    protected $table = 'Seats';
    protected $primaryKey= 'email';
    protected $returnType= 'App\Entities\Seat';
    protected $allowedFields= ['idPro', 'rowNumber', 'seatNumber', 'status', 'idRes'];
}
