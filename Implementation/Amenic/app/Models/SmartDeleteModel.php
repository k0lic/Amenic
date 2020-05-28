<?php namespace App\Models;

/*
    Author: Andrija Kolić
    Github: k0lic
*/

use CodeIgniter\Model;
use Exception;

/**
 *  Abstract base model that provides the SmartDelete interface.
 *  The idea of SmartDelete is to delete all dependencies with the chosen entry.
 * 
 *  @version 1.0
 */
abstract class SmartDeleteModel extends Model
{
    /**
     *  Wraps SmartDelete in a transaction.
     *  Smart delete is used to delete an entry with all its dependant entities.
     * 
     *  @param string $key primary key - used to select an entry from the appropriate table
     * 
     *  @return void
     * 
     *  @throws Exception
     */
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
