<?php namespace App\Models;

use CodeIgniter\Model;
use App\Models\ProjectionModel;
use App\Models\RoomTechnologyModel;
use App\Entities\RoomTechnology;
use App\Entities\Projection;
use Exception;

/**
 *  Model used for database operations focused on the 'Rooms' table.
 * 
 *  @version 1.0
 */
class RoomModel extends Model
{
    /**
     *  @var string $table table name
     */
    protected $table = 'Rooms';

    /**
     *  @var string $primaryKey primary key name
     */
    protected $primaryKey= 'email';

    /**
     *  @var object $returnType the type of the return objects for methods of this class
     */
    protected $returnType= 'App\Entities\Room';

    /**
     *  @var array $allowedFields fields in the table that can be manipulated using this model
     */
    protected $allowedFields = ['name','email','numberOfRows','seatsInRow'];

    /**
     *  Deletes the room and all of its technologies and projections.
     * 
     *  @param string $email email of the chosen cinema account
     *  @param string $name name of the chosen room
     * 
     *  @return void
     */
    public function smartDelete($email, $name)
    {
        $promdl = new ProjectionModel();
        $rtmdl = new RoomTechnologyModel();
        $projections = $promdl->where("email", $email)->where("roomName", $name)->findAll();
        foreach ($projections as $pro)
            $promdl->smartDelete($pro->idPro);
        $rtmdl->where("email", $email)->where("name", $name)->delete();
        $this->where("email", $email)->where("name", $name)->delete();
    }

    /**
     *  Wraps smartDelete() into a transaction.
     * 
     *  @param string $email email of the chosen cinema account
     *  @param string $name name of the chosen room
     * 
     *  @return void
     * 
     *  @throws Exception
     */
    public function transSmartDelete($email, $name)
    {
        try
        {
            $this->db->transBegin();
            $this->smartDelete($email, $name);
            $this->db->transCommit();
        }
        catch (Exception $e)
        {
            $this->db->transRollback();
            throw new Exception("Transaction ".get_class($this).".transSmartDelete(".$email.",".$name.") failed!<br/>".$e->getMessage());
        }
    }

    /**
     *  Modifies a room in the cinema.
     *  If the room name didn't change, performs update of room with new parameters in place.
     *  Otherwise inserts a new room while deleting an old one. Transfers all of the projections to the new room.
     * 
     *  Accomplishes editing the 'name' part of the rooms table primary key, and updates the other fields as well.
     * 
     *  @param string $email email of the chosen cinema account
     *  @param string $oldName name of the chosen room
     *  @param object $newRoom the chosen room after the changes - prebuilt
     *  @param array $newTech the technologies supported in the room after the changes
     * 
     *  @return void
     */
    public function smartReplace($email, $oldName, $newRoom, $newTech)
    {
        $promdl = new ProjectionModel();
        $rtmdl = new RoomTechnologyModel();

        $newName = $newRoom->name;
        $rtmdl->where("name", $oldName)->where("email", $email)->delete();                                          // scrap old room technologies
        if ($newName != $oldName)
        {
            $this->insert($newRoom);                                                                                // insert new room if needed
            $promdl->where("email", $email)->where("roomName", $oldName)->set(["roomName" => $newName])->update();  // move projections from old to new room
            $this->where("email", $email)->where("name", $oldName)->delete();                                       // scrap old room
        } 
        else
        {                                                                                                           // updates rows and columns in place
            $this->where("email", $email)->where("name", $newName)->set(["numberOfRows" => $newRoom->numberOfRows,"seatsInRow" => $newRoom->seatsInRow])->update();
        }
            
        foreach ($newTech as $techId)                                                                               // insert technologies into new room
            $rtmdl->insert(new RoomTechnology([
                "name" => $newName,
                "email" => $email,
                "idTech" => $techId
            ]));
    }

    /**
     *  Wraps smartReplace() into a transaction.
     * 
     *  @param string $email email of the chosen cinema account
     *  @param string $oldName name of the chosen room
     *  @param object $newRoom the chosen room after the changes - prebuilt
     *  @param array $newTech the technologies supported in the room after the changes
     * 
     *  @return void
     * 
     *  @throws Exception
     */
    public function transSmartReplace($email, $oldName, $newRoom, $newTech)
    {
        try
        {
            $this->db->transBegin();
            $this->smartReplace($email, $oldName, $newRoom, $newTech);
            $this->db->transCommit();
        }
        catch (Exception $e)
        {
            $this->db->transRollback();
            throw new Exception("Transaction ".get_class($this).".transSmartReplace(".$email.",".$oldName.",...) failed!<br/>".$e->getMessage());
        }
    }
}
