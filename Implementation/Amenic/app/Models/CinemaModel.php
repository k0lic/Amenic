<?php namespace App\Models;

/*
    Author: Andrija Kolić
    Github: k0lic
*/

use App\Models\SmartDeleteModel;
use App\Models\UserModel;
use App\Models\WorkerModel;
use App\Models\GalleryModel;
use App\Models\ComingSoonModel;
use App\Models\RoomModel;
use App\Models\ProjectionModel;

/**
 *  Model used for database operations focused on the 'Cinemas' table.
 *  Extends the SmartDeleteModel.
 * 
 *  @version 1.0
 */
class CinemaModel extends SmartDeleteModel
{
    /**
     *  @var string $table table name
     */
    protected $table = 'Cinemas';

    /**
     *  @var string $primaryKey primary key name
     */
    protected $primaryKey= 'email';

    /**
     *  @var object $returnType the type of the return objects for methods of this class
     */
    protected $returnType= 'App\Entities\Cinema';
    
    /**
     *  @var array $allowedFields fields in the table that can be manipulated using this model
     */
    protected $allowedFields= ['email', 'name', 'address', 'phoneNumber', 'description', 'mngFirstName', 'mngLastName', 'mngPhoneNumber', 'mngEmail', 'banner', 'approved', 'closed', 'idCountry', 'idCity'];  

    /**
     *  Deletes the entry from the 'Cinemas' table along with all of its dependants: galleries, comingsoon, rooms, workers; and its base object.
     * 
     *  @param string $email email address of the chosen cinema account
     * 
     *  @return void
     */
    public function smartDelete($email)
    {
        $usermdl = new UserModel();
        $workermdl = new WorkerModel();
        $gallerymdl = new GalleryModel();
        $soonmdl = new ComingSoonModel();
        $roommdl = new RoomModel();

        $gallerymdl->where("email", $email)->delete();                                  // deletes all gallery photos
        $soonmdl->where("email", $email)->delete();                                     // deletes all movies that are coming soon
        $workers = $workermdl->where("idCinema", $email)->findAll();                    // gets all of the workers
        foreach ($workers as $worker)
            $workermdl->smartDelete($worker->email);                                    // deletes all of the workers
        $rooms = $roommdl->where("email", $email)->findAll();                           // gets all of the room names
        foreach ($rooms as $room)
            $roommdl->smartDelete($email,$room->name);                                  // deletes all of the rooms
        $this->delete($email);                                                          // deletes the Cinema entry
        $usermdl->smartDelete($email);                                                  // deletes the base object
    }

    /**
     *  Closes the cinema, which involves canceling all the projections. Wraps the operation in a transaction.
     * 
     *  @param string $email email address of the chosen cinema account
     * 
     *  @return void
     * 
     *  @throws Exception
     */
    public function transSmartClose($email)
    {
        try
        {
            $this->db->transBegin();

            $promdl = new ProjectionModel();
            $projections = $promdl->where("email", $email)->findAll();
            foreach ($projections as $pro)
                $promdl->smartCancel($pro->idPro);
            $this->update($email, ["closed" => 1]);
            // mailOwner($email); ============================================================================= TODO
            
            $this->db->transCommit();
        }
        catch (Exception $e)
        {
            $this->db->transRollback();
            throw new Exception("Transaction ".get_class($this).".transSmartClose(".$email.") failed!<br/>".$e->getMessage());
        }
    }
}
