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

    public function getMyWorkersWithImages($email)
    {
        $usermdl = new UserModel();
        $cinemaWorkers = $this->where("idCinema", $email)->find();
        $results = [];
        foreach ($cinemaWorkers as $worker)
        {
            $image = ($usermdl->find($worker->email))->image;
            array_push($results, [
                "worker" => $worker,
                "image" => $image
            ]);
        }
        return $results;
    }

    public function getMyWorkersLikeWithImages($email, $match)
    {
        $usermdl = new UserModel();
        $cinemaWorkers = $this->where("idCinema", $email)->groupStart()->like("firstName", $match)->orLike("lastName", $match)->orLike("email", $match)->groupEnd()->find();
        $results = [];
        foreach ($cinemaWorkers as $worker)
        {
            $image = ($usermdl->find($worker->email))->image;
            array_push($results, [
                "worker" => $worker,
                "image" => $image
            ]);
        }
        return $results;
    }
}
