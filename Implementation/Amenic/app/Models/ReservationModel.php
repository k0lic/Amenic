<?php namespace App\Models;

/*
    Author: Andrija Kolić
    Github: k0lic
*/

use App\Models\SmartDeleteModel;
use App\Models\SeatModel;

/**
 *  Model used for database operations focused on the 'Reservations' table.
 *  Extends the SmartDeleteModel.
 * 
 *  @version 1.0
 */
class ReservationModel extends SmartDeleteModel
{
    /**
     *  @var string $table table name
     */
    protected $table = 'Reservations';

    /**
     *  @var string $primaryKey primary key name
     */
    protected $primaryKey= 'idRes';

    /**
     *  @var object $returnType the type of the return objects for methods of this class
     */
    protected $returnType= 'App\Entities\Reservation';

    /**
     *  @var array $allowedFields fields in the table that can be manipulated using this model
     */
    protected $allowedFields = ['idRes','confirmed','idPro','email'];

    /**
     *  Deletes the reservation entry, and updates all of the reserved seats to 'free'.
     * 
     *  @param string $idRes id of the chosen reservation
     * 
     *  @return void
     */
    public function smartDelete($idRes)
    {
        $seatmdl = new SeatModel();
        $seatmdl->where("idRes", $idRes)->set(["idRes" => null,"status" => "free"])->update();
        $this->delete($idRes);
    }

    /**
     *  Updates the confirm field to true as well as updating all of the reserved seats to 'sold'.
     * 
     *  @param string $idRes id of the chosen reservation
     * 
     *  @return void
     */
    public function smartConfirm($idRes)
    {
        $seatmdl = new SeatModel();
        $seatmdl->where("idRes", $idRes)->set(["status" => "sold"])->update();
        $this->update($idRes, ["confirmed" => 1]);
    }

    /**
     *  Wraps smartConfirm() in a transaction.
     * 
     *  @param string $idRes id of the chosen reservation
     * 
     *  @return void
     * 
     *  @throws Exception
     */
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
