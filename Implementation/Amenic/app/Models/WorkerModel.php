<?php namespace App\Models;

/*
    Author: Andrija Kolić
    Github: k0lic
*/

use App\Models\SmartDeleteModel;
use App\Models\UserModel;

/**
 *  Model used for database operations focused on the 'Workers' table.
 *  Extends the SmartDeleteModel.
 * 
 *  @version 1.0
 */
class WorkerModel extends SmartDeleteModel
{
    /**
     *  @var string $table table name
     */
    protected $table = 'Workers';

    /**
     *  @var string $primaryKey primary key name
     */
    protected $primaryKey= 'email';

    /**
     *  @var object $returnType the type of the return objects for methods of this class
     */
    protected $returnType= 'App\Entities\Worker';

    /**
     *  @var array $allowedFields fields in the table that can be manipulated using this model
     */
    protected $allowedFields = ['email','idCinema','firstName','lastName'];

    /**
     *  Deletes the user base object with the worker.
     * 
     *  @param string $email email of the chosen worker account
     * 
     *  @return void
     */
    public function smartDelete($email)
    {
        $usermdl = new UserModel();
        $this->delete($email);
        $usermdl->smartDelete($email);
    }

    /**
     *  Creates a worker account with a base user entry. Wraps the operation into a transaction.
     * 
     *  @param object $worker the prebuilt worker entry
     *  @param object $user the prebuilt user entry
     * 
     *  @return void
     * 
     *  @throws Exception
     */
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

    /**
     *  Finds all of the workers of the chosen cinema. Attaches profile pictures to them all.
     * 
     *  @param string $email email address of the chosen cinema account
     * 
     *  @return array workers with attached profile pictures
     */
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

    /**
     *  Searches the workers of the chosen cinema by matching with: first name, last name, email address.
     *  Attaches profile pictures to them all.
     * 
     *  @param string $email email address of the chosen cinema account
     *  @param string $match the search term
     * 
     *  @return array workers with attached profile pictures
     */
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
