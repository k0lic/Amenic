<?php namespace App\Models;

use App\Models\SmartDeleteModel;
use App\Models\UserModel;
use App\Models\WorkerModel;
use App\Models\GalleryModel;
use App\Models\ComingSoonModel;
use App\Models\RoomModel;
use App\Models\ProjectionModel;

class CinemaModel extends SmartDeleteModel
{
    protected $table = 'Cinemas';
    protected $primaryKey= 'email';
    protected $returnType= 'App\Entities\Cinema';  
    protected $allowedFields= ['email', 'name', 'address', 'phoneNumber', 'description', 'mngFirstName', 'mngLastName', 'mngPhoneNumber', 'mngEmail', 'banner', 'approved', 'closed', 'idCountry', 'idCity'];  

    // Deletes the entry from the 'Cinemas' table along with all of its dependants: galleries, comingsoon, rooms, workers; and its base object.
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

    // Closes the cinema, which involves canceling all the projections.
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
