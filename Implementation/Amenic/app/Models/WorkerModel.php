<?php namespace App\Models;

use App\Models\SmartDeleteModel;
use App\Models\UserModel;

class WorkerModel extends SmartDeleteModel
{
    protected $table = 'Workers';
    protected $primaryKey= 'email';
    protected $returnType= 'App\Entities\Worker';
    protected $allowedFields = ['email','idCinema','firstName','lastName'];

    // Deletes the user base object with the worker.
    public function smartDelete($email)
    {
        $usermdl = new UserModel();
        $this->delete($email);
        $usermdl->smartDelete($email);
    }

    public function transSmartCreate($worker, $user)
    {
        try
        {
            $this->db->transBegin();

            $usermdl = new UserModel();
            $usermdl->insert($user);
            $this->insert($worker);

            $this->db->transCommit();
        }
        catch (Exception $e)
        {
            $this->db->transRollback();
            throw new Exception("Transaction ".get_class($this).".transSmartCreate() failed!<br/>".$e->getMessage());
        }
    }
}
