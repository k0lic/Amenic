<?php namespace App\Models;

/*
    Author: Andrija Kolić
    Github: k0lic
*/

use App\Models\SmartDeleteModel;
use App\Models\SeatModel;
use App\Models\ReservationModel;

class ProjectionModel extends SmartDeleteModel
{
    protected $table = 'Projections';
    protected $primaryKey= 'idPro';
    protected $returnType= 'App\Entities\Projection';   
    protected $allowedFields = ['idPro','roomName','email','dateTime','price','canceled','tmdbID','idTech'];
    
    // Deletes the projection along with all of the dependant seats and reservations.
    public function smartDelete($idPro)
    {
        $seatmdl = new SeatModel();
        $resmdl = new ReservationModel();
        $seatmdl->where("idPro", $idPro)->delete();
        $reservationIds = $resmdl->where("idPro", $idPro)->findColumn("idRes");
        foreach ($reservationIds as $id)
            $resmdl->delete($id);   // mail if the projection hasn't started yet ======================================== TODO
        $this->delete($idPro);
    }

    // Cancels the projection, deleting all the reservations.
    public function smartCancel($idPro)
    {
        $resmdl = new ReservationModel();
        $reservationIds = $resmdl->where("idPro", $idPro)->findColumn("idRes");
        foreach ($reservationIds as $id)
            $resmdl->delete($id);   // change to deleteAndMail at some point ============================================= TODO
        $this->update($idPro, ["canceled" => 1]);
    }

    // Wraps smartCancel() into a transaction.
    public function transSmartCancel($idPro)
    {
        try
        {
            $this->db->transBegin();
            $this->smartCancel($idPro);
            $this->db->transCommit();
        }
        catch (Exception $e)
        {
            $this->db->transRollback();
            throw new Exception("Transaction ".get_class($this).".transSmartCancel(".$idPro.") failed!<br/>".$e->getMessage());
        }
    }

    // Changes the start time of the projection.
    public function smartChangeTime($idPro, $newStartTime)
    {
        /*
        ================================================================================================================ TODO
        $resmdl = new ReservationModel();
        $reservationIds = $resmdl->where("idPro", $idPro)->findColumn("idRes");
        foreach ($reservationIds as $id)
            $resmdl->mailOwner($id);
        */
        $this->update($idPro, ["dateTime" => 1]);
    }

    // Returns all projections in a given cinema, ordered by start date and time.
    public function findAllProjectionsOfMyCinema($cinemaEmail)
    {
        $projections = $this->where('email',$cinemaEmail)->orderBy('dateTime','ASC')->findAll();
        return $projections;
    }
}

?>