<?php namespace App\Models;

use App\Models\SmartDeleteModel;
use App\Models\SeatModel;

class ReservationModel extends SmartDeleteModel
{
    protected $table = 'Reservations';
    protected $primaryKey= 'idRes';
    protected $returnType= 'App\Entities\Reservation';  
    protected $allowedFields = ['idRes','confirmed','idPro','email'];

    // Deletes the reservation entry, and updates all of the reserved seats to 'free'.
    public function smartDelete($idRes)
    {
        $seatmdl = new SeatModel();
        $seatmdl->where("idRes", $idRes)->set(["idRes" => null,"status" => "free"])->update();
        $this->delete($idRes);
    }

    // Updates the confirm field to true as well as updating all of the reserved seats to 'sold'.
    public function smartConfirm($idRes)
    {
        $seatmdl = new SeatModel();
        $seatmdl->where("idRes", $idRes)->set(["status" => "sold"])->update();
        $this->update($idRes, ["confirmed" => 1]);
    }

    // Wraps smartConfirm() in a transaction.
    public function transSmartConfirm($idRes)
    {
        try
        {
            $this->db->transBegin();
            $this->smartConfirm($idRes);
            $this->db->transCommit();
        }
        catch (Exception $e)
        {
            $this->db->transRollback();
            throw new Exception("Transaction ".get_class($this).".transSmartConfirm(".$idRes.") failed!<br/>".$e->getMessage());
        }
    }
}
