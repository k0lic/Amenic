<?php namespace App\Models;

use CodeIgniter\Model;
use Exception;

abstract class SmartDeleteModel extends Model
{
    // Invokes smartDelete within a transaction.
    // Smart delete is used to delete an entry with all its dependant entities.
    public function transSmartDelete($key)
    {
        try
        {
            $this->db->transBegin();
            $this->smartDelete($key);
            $this->db->transCommit();
        }
        catch (Exception $e)
        {
            $this->db->transRollback();
            throw new Exception("Transaction ".get_class($this).".transSmartDelete(".$key.") failed!<br/>".$e->getMessage());
        }
    }
}
