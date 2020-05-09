<?php namespace App\Models;

/*
    Author: Andrija Kolić
    Github: k0lic
*/

use CodeIgniter\Model;
use App\Models\MovieModel;
use App\Models\ProjectionModel;
use App\Models\ComingSoonModel;

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

}

?>