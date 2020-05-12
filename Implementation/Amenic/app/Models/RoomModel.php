<?php namespace App\Models;

use CodeIgniter\Model;
use App\Models\ProjectionModel;
use App\Models\RoomTechnologyModel;
use App\Entities\RoomTechnology;
use App\Entities\Projection;
use Exception;

class RoomModel extends Model
{
    #opet kompozitni primarni kljuc
    #find($id)–findAll()–findAll($limit, $offset)–first()–where($name, $value)–insert($data)–update($id, $data)–save($data)–delete($id)
    protected $table = 'Rooms';
    protected $primaryKey= 'email';
    protected $returnType= 'App\Entities\Room';
    protected $allowedFields = ['name','email','numberOfRows','seatsInRow'];

    // Deletes the room and all of its technologies and projections.
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

    // Wraps smartDelete() into a transaction.
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

    // If the room name didn't change, performs update of room with new parameters in place.
    // 
    // Otherwise inserts a new room while deleting an old one. Transfers all of the projections to the new room.
    // Accomplishes editing the 'name' part of the rooms table primary key, and updates the other fields as well.
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

    // Wraps smartReplace() into a transaction.
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
