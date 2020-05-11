<?php namespace App\Controllers;

/*
    Author: Miloš Živkovic
    Github: zivkovicmilos
*/

use App\Models\MovieModel;

class Movie extends BaseController {

    public function index() {

        $movieModel = new MovieModel();

        $movie = $movieModel->find(437068);
        
        return view('Movies/movie.php', ['movie' => $movie]);
    }

}