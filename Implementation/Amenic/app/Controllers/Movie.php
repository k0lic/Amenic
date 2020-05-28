<?php namespace App\Controllers;

/*
    Author: Miloš Živkovic
    Github: zivkovicmilos
*/

use \App\Models\UserModel;
use App\Models\MovieModel;
use App\Models\ProjectionModel;
use App\Models\CinemaModel;
use App\Models\CityModel;
use App\Models\CountryModel;
use App\Models\TechnologyModel;

use function App\Helpers\isAuthenticated;
use function App\Helpers\isValid;
use function App\Helpers\generateToken;
use function App\Helpers\setToken;

use CodeIgniter\I18n\Time;

use function App\Helpers\getReviews;

/** Movie – Controller for the movie search page
 * 
 * @version 1.0
 * */

class Movie extends BaseController {

    /**
     * Checks the user's token
     * @return object
     */

    private function getToken() {
        helper('auth');

        if (isset($_COOKIE['token'])) {
            $tokenCookie = $_COOKIE['token'];   
            $token = isValid($tokenCookie);
            
            if ($token && isAuthenticated("RUser")) {
                $image = (new UserModel())->find($token->email);
                $image = $image->image;
        
                $token->image = $image;
                return $token;
            }
        }
        return null;
    }

    /**
     * Returns the movie search page view
     * @param TMDBID of the projection in the database
     * @return view
     */

    public function index($tmdbID) {

        $authenticated = "false";

        $token = $this->getToken();

        if (!is_null($token)){
            $authenticated = "true";
        }

        helper('imdb_helper');

        $movieModel = new MovieModel();
        
        $movie = $movieModel->find($tmdbID);

        $reviews = getReviews($movie->imdbID);
        
        return view('Movies/movie.php', ['movie' => $movie, 'reviews' => $reviews, 'authenticated' => $authenticated, 'token' => $token]);
    }

    /**
     * Gets all the projections for a specific movie
     * @return JSON
     */

    public function getProjections() {
        
        $tmdbID = $_REQUEST['tmdbID'];

        $dateTimePattern = '/(.*)-(.*)-(.*)/';
        preg_match($dateTimePattern, $_REQUEST['date'], $dateMatches);

        $dateYear = (int)   $dateMatches[1];
        $dateMonth = (int)  $dateMatches[2];
        $dateDay =  (int)   $dateMatches[3];
        
        $time =     $_REQUEST['time'];
        $cinema =   $_REQUEST['cinema'];
        $country =  $_REQUEST['country'];
        $city =     $_REQUEST['city'];

        $timeSet = strcmp($time, '');

        $timeArr = [0,0,0];

        if($timeSet) {
            $timeArr = explode(':', $time);
        }

        if($cinema == '') {
            $cinema = '*';
        }

        $var = !$timeSet?' >=':' =';
        $var1 = '';

        if($city == '') {
            $city = 0;
            $var1 = '>=';
        }

        $projectionModel = new ProjectionModel();

        $date = Time::create($dateYear, $dateMonth, $dateDay, $timeArr[0], $timeArr[1], $timeArr[2]);
        $endOfDay= $date->addDays(1);

        $results = $projectionModel
                    ->where('tmdbID', $tmdbID)
                    ->where('dateTime'.$var, $date)
                    ->where('dateTime <', $endOfDay)
                    ->join('Cinemas', 'Projections.email = Cinemas.email')
                    ->where('idCountry', $country)
                    ->where('idCity'.$var1, $city)
                    ->findAll();

        $newRes = $results;

        if($_REQUEST['cinema'] != '') {
            $newRes = array_filter($results, function($result) {
                //return !strcmp($result['email'], $_REQUEST['cinema']);
                return $result->email == $_REQUEST['cinema'];
            });
        }
        
        echo json_encode($newRes);
    }

    /**
     * Returns the cinema name
     * @return JSON
     */

    public function getCinemaName() {
        $email = $_REQUEST['email'];
        $cinemasModel = new CinemaModel();
        $result = $cinemasModel->find($email);

        echo json_encode($result);
    }

    /**
     * Returns the cinema's profile image
     * @return JSON
     */

    public function getCinemaImage() {
        $email = $_REQUEST['email'];
        $userModel = new UserModel();
        $result = $userModel->find($email);

        echo json_encode($result);
    }

    /**
     * Returns the technology name based on the id
     * @return JSON
     */

    public function getTechName() {
        $idTech = $_REQUEST['idTech'];
        $techModel = new TechnologyModel();
        $result = $techModel->find($idTech);

        echo json_encode($result);
    }

    /**
     * Returns all of the countries
     * @return JSON
     */
    public function getCountries() {
        $countryModel = new CountryModel();
        $results = $countryModel->findAll();

        echo json_encode($results);
    }

    /**
     * Returns all of the cities for a specific country
     * @return JSON
     */
    public function getCities() {

        $idCountry = $_REQUEST['country'];

        $cityModel = new CityModel();
        $results = $cityModel
                    ->where('idCountry', $idCountry)
                    ->findAll();


        echo json_encode($results);
    }

    /**
     * Returns all of the cinemas in a specific country / city
     * @return JSON
     */
    public function getCinemas() {
        $idCountry = $_REQUEST['country'];
        $idCity = $_REQUEST['city'];
        $tmdbID = $_REQUEST['tmdbID'];

        $var = '';

        if($idCity == '') {
            $var = ' >=';
            $idCity = 0;
        }

        $cinemaModel = new CinemaModel();

        $results = $cinemaModel
                ->where('idCity'.$var, $idCity)
                ->where('idCountry', $idCountry)
                ->join('Projections', 'Cinemas.email = Projections.email')
                ->where('tmdbID', $tmdbID)
                ->findAll();
        
        echo json_encode($results);
    }

    /**
     * Returns all of the times a projection is showing
     * @return JSON
     */

    public function getTimes() {
        $tmdbID = $_REQUEST['tmdbID'];

        $dateTimePattern = '/(.*)-(.*)-(.*)/';
        preg_match($dateTimePattern, $_REQUEST['date'], $dateMatches);

        $dateYear = (int)   $dateMatches[1];
        $dateMonth = (int)  $dateMatches[2];
        $dateDay =  (int)   $dateMatches[3];

        $date = Time::create($dateYear, $dateMonth, $dateDay, 0, 0, 0);
        $endOfDay= $date->addDays(1);

        $projectionModel = new ProjectionModel();

        $results = $projectionModel
                    ->where('tmdbID', $tmdbID)
                    ->where('dateTime >=', $date)
                    ->where('dateTime <', $endOfDay)
                    ->findAll();

        $newRes = $results;

        if($_REQUEST['cinema'] != '') {
            $newRes = array_filter($results, function($result) {
                return $result->email == $_REQUEST['cinema'];
            });
        }

        echo json_encode($newRes);
    }
}