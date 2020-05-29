<?php namespace App\Models;

/*
    Author: Andrija Kolić
    Github: k0lic
*/

use App\Models\SmartDeleteModel;
use App\Models\SeatModel;
use App\Models\ReservationModel;
use App\Models\RoomModel;
use App\Models\ComingSoonModel;
use App\Models\MovieModel;
use App\Models\CinemaModel;
use App\Models\TechnologyModel;
use App\Entities\Seat;
use Exception;

use function App\Helpers\sendReservationInfo;
use function App\Helpers\sendMailOnReservationDelete;

date_default_timezone_set("Europe/Belgrade");

/**
 *  Model used for database operations focused on the 'Projections' table.
 *  Extends the SmartDeleteModel.
 * 
 *  @version 1.0
 */
class ProjectionModel extends SmartDeleteModel
{
    /**
     *  @var string $table table name
     */
    protected $table = 'Projections';

    /**
     *  @var string $primaryKey primary key name
     */
    protected $primaryKey= 'idPro';

    /**
     *  @var object $returnType the type of the return objects for methods of this class
     */
    protected $returnType= 'App\Entities\Projection';

    /**
     *  @var array $allowedFields fields in the table that can be manipulated using this model
     */
    protected $allowedFields = ['roomName','email','dateTime','price','canceled','tmdbID','idTech'];
    
    /**
     *  Deletes the projection along with all of the dependant seats and reservations. Sends an email to all reservation holders if the projection didn't start.
     * 
     *  @param string $idPro id of the chosen projection
     * 
     *  @return void
     */
    public function smartDelete($idPro)
    {
        $seatmdl = new SeatModel();
        $resmdl = new ReservationModel();
        $seatmdl->where("idPro", $idPro)->delete();
        $reservations = $resmdl->where("idPro", $idPro)->findAll();
        
        $projection = $this->find($idPro);
        if (strtotime($projection->dateTime) > time())
        {
            // send email about reservation deletion
            helper('mailer_helper');

            foreach ($reservations as $res)
            {
                sendMailOnReservationDelete($res);
                $resmdl->delete($res->idRes);
            }
        }
        else
        {
            // do not send any emails, since this projection already started playing
            foreach ($reservations as $res)
                $resmdl->delete($res->idRes);
        }

        $this->delete($idPro);
    }

    /**
     *  Cancels the projection, deleting all the reservations.
     * 
     *  @param string $idPro id of the chosen projection
     * 
     *  @return void
     */
    public function smartCancel($idPro)
    {
        $resmdl = new ReservationModel();
        $reservations = $resmdl->where("idPro", $idPro)->findAll();
        helper('mailer_helper');
        foreach ($reservations as $res)
        {
            sendMailOnReservationDelete($res);
            $resmdl->delete($res->idRes);
        }
        $this->update($idPro, ["canceled" => 1]);
    }

    /**
     *  Wraps smartCancel() into a transaction.
     * 
     *  @param string $idPro id of the chosen projection
     * 
     *  @return void
     * 
     *  @throws Exception
     */
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

    /**
     *  Creates a projection, along with all of its seats. Deletes the coming soon entry if it exists.
     *  Wraps the operation into a transaction.
     * 
     *  @param object $pro the prebuilt projection entry
     * 
     *  @return void
     * 
     *  @throws Exception
     */
    public function transSmartCreate($pro)
    {
        try
        {
            $this->db->transBegin();

            $roommdl = new RoomModel();
            $seatmdl = new SeatModel();
            $soonmdl = new ComingSoonModel();
            $this->insert($pro);
            $inserted = $this->where("email", $pro->email)->where("roomName", $pro->roomName)->where("dateTime", $pro->dateTime)->findAll()[0];
            $room = $roommdl->where("email", $pro->email)->where("name", $pro->roomName)->findAll()[0];
            $seats = [];
            for ($i=1;$i<=$room->numberOfRows;$i++)
            {
                for ($j=1;$j<=$room->seatsInRow;$j++)
                {
                    $seat = new Seat([
                        "idPro" => $inserted->idPro,
                        "rowNumber" => $i,
                        "seatNumber" => $j,
                        "status" => "free",
                        "idRes" => null
                    ]);
                    $seatmdl->insert($seat);
                }
            }
            $soonmdl->where("email", $pro->email)->where("tmdbID", $pro->tmdbID)->delete();

            $this->db->transCommit();
        }
        catch (Exception $e)
        {
            $this->db->transRollback();
            throw new Exception("Transaction ".get_class($this).".transSmartCreate() failed!<br/>".$e->getMessage());
        }
    }

    /**
     *  Changes the start time of the projection. Sends an email to every affected reservation.
     * 
     *  @param string $idPro id of the chosen projection
     *  @param time $newStartTime new start time
     * 
     *  @return void
     */
    public function smartChangeTime($idPro, $newStartTime)
    {
        $this->update($idPro, ["dateTime" => $newStartTime]);

        helper('mailer_helper');
        $resmdl = new ReservationModel();
        $moviemdl = new MovieModel();
        $cinemamdl = new CinemaModel();
        $techmdl = new TechnologyModel();
        $seatmdl = new SeatModel();
        $reservations = $resmdl->where("idPro", $idPro)->findAll();
        $projection = $this->find($idPro);
        $movie = $moviemdl->find($projection->tmdbID);
        $cinema = $cinemamdl->find($projection->email);
        $tech = $techmdl->find($projection->idTech);
        foreach ($reservations as $res)
        {
            $seats = $seatmdl->where("idRes", $res->idRes)->find();
            $seatString = "";
            foreach ($seats as $seat)
            {
                $seatString .= chr(64 + $seat->rowNumber)."".$seat->seatNumber." ";
            }
            sendReservationInfo($res, $seatString, $projection, $movie, $cinema, $tech, true);
        }
    }

    /**
     *  Returns all projections in a given cinema, ordered by start date and time.
     * 
     *  @param string $cinemaEmail email address of the chosen cinema account
     * 
     *  @return array projections
     */
    public function findAllProjectionsOfMyCinema($cinemaEmail)
    {
        $projections = $this->where('email',$cinemaEmail)->orderBy('dateTime','ASC')->findAll();
        return $projections;
    }
}

?>