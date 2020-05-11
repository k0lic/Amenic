<?php namespace App\Models;

use App\Models\SmartDeleteModel;
use App\Models\UserModel;

class WorkerModel extends SmartDeleteModel
{
    protected $table = 'Workers';
    protected $primaryKey= 'email';
    protected $returnType= 'App\Entities\Worker';

    // Deletes the user base object with the worker.
    public function smartDelete($email)
    {
        $usermdl = new UserModel();
        $this->delete($email);
        $usermdl->smartDelete($email);
    }
}
