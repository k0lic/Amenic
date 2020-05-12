<?php namespace App\Controllers;

/*
    Author: Miloš Živkovic
    Github: zivkovicmilos
*/

use App\Models\MovieModel;

use function App\Helpers\getReviews;

class Movie extends BaseController {

    public function index() {

        helper('imdb_helper');

        $movieModel = new MovieModel();
        
        $movie = $movieModel->find(437068);

        $reviews = getReviews($movie->imdbID);
        
        return view('Movies/movie.php', ['movie' => $movie, 'reviews' => $reviews]);
    }

}