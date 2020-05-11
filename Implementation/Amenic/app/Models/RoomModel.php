<?php namespace App\Models;

use CodeIgniter\Model;
use App\Models\ProjectionModel;
use App\Models\RoomTechnologyModel;
use App\Entities\RoomTechnology;

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
        $projectionIds = $promdl->where("email", $email)->where("roomName", $name)->findColumn("idPro");
        foreach ($projectionIds as $id)
            $promdl->smartDelete($id);
        $rtmdl->where("email", $email)->where("name", $name)->delete();
        $this->delete($email);
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

    // Inserts a new room while deleting an old one. Transfers all of the projections to the new room.
    // Accomplishes editing the 'name' part of the rooms table primary key, and updates the other fields as well.
    public function smartReplace($email, $oldName, $newRoom, $newTech)
    {
        $promdl = new ProjectionModel();
        $rtmdl = new RoomTechnologyModel();

        $newName = $newRoom->name;
        $this->insert($newRoom);                                                                                // insert new room
        $promdl->where("email", $email)->where("roomName", $oldName)->set(["roomName" => $newName])->update();  // move projections from old to new room
        foreach ($newTech as $techId)                                                                           // inert technologies into new room
            $rtmdl->insert(new RoomTechnology([
                "name" => $newName,
                "email" => $email,
                "idTech" => $techId
            ]));
        $rtmdl->where("name", $oldName)->where("email", $email)->delete();                                      // scrap old room technologies
        $this->where("email", $email)->where("name", $oldName)->delete();                                       // scrap old room
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
