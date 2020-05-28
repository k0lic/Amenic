<?php namespace App\Models;

/*
    Author: Andrija Kolić
    Github: k0lic
*/

use CodeIgniter\Model;
use App\Models\MovieModel;
use App\Models\ProjectionModel;
use App\Models\ComingSoonModel;
use App\Models\RoomModel;
use App\Models\TechnologyModel;
use App\Models\RoomTechnologyModel;
use App\Models\SeatModel;
use App\Entities\RoomTechnology;
use Exception;

class AACinemaModel extends Model
{
    protected $returnType= 'object';

    public function findAllProjectionsOfMyCinemaAndAttachPosters($cinemaEmail)
    {
        $movieModel = new MovieModel();
        $projectionModel = new ProjectionModel();
        $projections = $projectionModel->findAllProjectionsOfMyCinema($cinemaEmail);
        $posters = [];
        $results = [];
        foreach ($projections as $projection)
        {
            $res["projection"] = $projection;
            if (isset($posters[$projection->tmdbID]))
            {
                $res["poster"] = $posters[$projection->tmdbID];
            }
            else
            {
                $movie = $movieModel->find($projection->tmdbID);
                if ($movie != null)
                {
                    $posters[$movie->tmdbID] = $movie->poster;
                    $res["poster"] = $movie->poster;
                }
                else
                {
                    $posters[$movie->tmdbID] = null;
                    $res["poster"] = null;
                }
            }
            array_push($results,$res);
        }
        return $results;
    }

    public function findAllComingSoonsOfMyCinemaAndAttachPosters($cinemaEmail)
    {
        $movieModel = new MovieModel();
        $comingSoonModel = new ComingSoonModel();
        $comingSoons = $comingSoonModel->where("email",$cinemaEmail)->findAll();
        $posters = [];
        $results = [];
        foreach ($comingSoons as $soon)
        {
            $res["soon"] = $soon;
            if (isset($posters[$soon->tmdbID]))
            {
                $res["poster"] = $posters[$soon->tmdbID];
            }
            else
            {
                $movie = $movieModel->find($soon->tmdbID);
                if ($movie != null)
                {
                    $posters[$movie->tmdbID] = $movie->poster;
                    $res["poster"] = $movie->poster;
                }
                else
                {
                    $posters[$movie->tmdbID] = null;
                    $res["poster"] = null;
                }
            }
            array_push($results,$res);
        }
        return $results;
    }

    public function addRoom($room,$technologies)
    {
        try
        {
            $this->db->transBegin();
            (new RoomModel())->insert($room);
            $rtm = new RoomTechnologyModel();
            foreach ($technologies as $tech)
            {
                $roomTech = new RoomTechnology([
                    "name" => $room->name,
                    "email" => $room->email,
                    "idTech" => $tech
                ]);
                $rtm->insert($roomTech);
            }
            $this->db->transCommit();
        }
        catch (Exception $e)
        {
            $this->db->transRollback();
            throw new Exception("AACinemaModel.addRoom() failed!<br/>".$e->getMessage());
        }
    }

    public function changeRoom($email,$oldName,$room,$technologies)
    {
        try
        {
            $this->db->transBegin();
            throw new Exception("NOT YET IMPLEMENTED!<br/>>");
            $this->db->transCommit();
        }
        catch (Exception $e)
        {
            $this->db->transRollback();
            throw new Exception("AACinemaModel.changeRoom() failed!<br/>".$e->getMessage());
        }
    }

    public function findAllProjectionsOfMyCinemaLike($email, $match)
    {
        $moviemdl = new MovieModel();
        $promdl = new ProjectionModel();
        $projectionIds = [];
        $moviePosters = [];

        // find by comparing with movie name
        $moviesLike = $moviemdl->like("title", $match)->find();
        $projectionsMovieNameLike = [];
        foreach ($moviesLike as $movie)
        {
            $projectionBatch = $promdl->where("email", $email)->where("tmdbID", $movie->tmdbID)->find();
            foreach ($projectionBatch as $pro)
            {
                array_push($projectionsMovieNameLike, $pro);
                $projectionIds[$pro->idPro] = 1;
            }
            $moviePosters[$movie->tmdbID] = $movie->poster;
        }

        // find by comparing with room name
        $projectionsRoomNameLike = $promdl->where("email", $email)->like("roomName", $match)->find();
        
        // combine
        $projectionsLike = $projectionsMovieNameLike;
        foreach ($projectionsRoomNameLike as $pro)
        {
            if (!isset($projectionIds[$pro->idPro]))
            {
                array_push($projectionsLike, $pro);
                $projectionIds[$pro->idPro] = 1;
            }
        }

        // attach posters
        $results = [];
        foreach ($projectionsLike as $pro)
        {
            $res["projection"] = $pro;
            if (isset($moviePosters[$pro->tmdbID]))
            {
                $res["poster"] = $moviePosters[$pro->tmdbID];
            }
            else
            {
                $movie = $moviemdl->find($pro->tmdbID);
                $moviePosters[$movie->tmdbID] = $movie->poster;
                $res["poster"] = $movie->poster;
            }
            array_push($results, $res);
        }

        // sort by start time
        for ($i=0;$i<count($results)-1;$i++)
        {
            for ($j=0;$j<(count($results)-1-$i);$j++)
            {
                $date1 = strtotime($results[$j]["projection"]->dateTime);
                $date2 = strtotime($results[$j+1]["projection"]->dateTime);
                if ($date1 > $date2) {
                    $tmp = $results[$j];
                    $results[$j] = $results[$j+1];
                    $results[$j+1] = $tmp;
                }
            }
        }

        return $results;
    }

    public function findAllComingSoonsOfMyCinemaLike($email, $match)
    {
        $moviemdl = new MovieModel();
        $soonmdl = new ComingSoonModel();

        $moviesLike = $moviemdl->like("title", $match)->find();
        $results = [];
        foreach ($moviesLike as $movie)
        {
            $soon = $soonmdl->where("email", $email)->where("tmdbID", $movie->tmdbID)->find();
            if ($soon != null)
            {
                $res["poster"] = $movie->poster;
                $res["soon"] = $soon[0];
                array_push($results, $res);
            }
        }

        return $results;
    }

    public function countMyMovieRepertoire($email, $day)
    {
        $promdl = new ProjectionModel();

        $dayOf = strtotime($day);
        $dayAfter = $dayOf + 24*60*60;
        $repertoireSize = $promdl
                            ->where("email", $email)
                            ->where("canceled", 0)
                            ->where("dateTime >=", date("Y-m-d H:i:s", $dayOf))
                            ->where("dateTime <", date("Y-m-d H:i:s", $dayAfter))
                            ->countAllResults();

        return $repertoireSize;
    }

    public function findMyMovieRepertoire($email, $day, $page)
    {
        $promdl = new ProjectionModel();

        $dayOf = strtotime($day);
        $dayAfter = $dayOf + 24*60*60;
        $projections = $promdl
                        ->where("email", $email)
                        ->where("canceled", 0)
                        ->where("dateTime >=", date("Y-m-d H:i:s", $dayOf))
                        ->where("dateTime <", date("Y-m-d H:i:s", $dayAfter))
                        ->orderBy('dateTime', 'ASC')
                        ->limit(20, ($page-1)*20)
                        ->find();

        $results = [];
        $moviemdl = new MovieModel();
        $techmdl = new TechnologyModel();
        $seatmdl = new SeatModel();
        foreach ($projections as $pro)
        {
            $res = [
                "idPro" => $pro->idPro,
                "movieName" => "",
                "startTime" => date("H:i", strtotime($pro->dateTime)),
                "roomName" => $pro->roomName,
                "type" => "",
                "price" => $pro->price,
                "freeSeats" => -1
            ];
            $movie = $moviemdl->find($pro->tmdbID);
            $res["movieName"] = $movie->title;
            $tech = $techmdl->find($pro->idTech);
            $res["type"] = $tech->name;
            $freeSeats = $seatmdl->where("idPro", $pro->idPro)->where("status", "free")->countAllResults();
            $res["freeSeats"] = $freeSeats;

            array_push($results, $res);
        }

        return $results;
    }

}

?>