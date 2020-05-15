<?php namespace App\Controllers;

/*
    Author: Martin Mitrović
    Github: Rpsaman13000
*/

use App\Models\MovieModel;
use App\Models\ComingSoonModel;
use App\Models\CinemaModel;
use App\Models\ProjectionModel;


/** HomeController – starting class which takes care of guests and registered users
 *  -provides list of all awailable movies
 *  -provides list of all awailable cinemas
 *  -registered users can change account information 
 *  @version 1.0
 */
class HomeController extends BaseController
{
	/** Function which returns movies currently playing
     * @return array[Movie] list of available movies
     */
	private function getPlayingMovies()
	{
		$movieArray=[];
		$projections = (new ProjectionModel())->select('tmdbID')->groupBy('tmdbID')->findAll();
		
		foreach($projections as $projection)
		{
			$movie = (new MovieModel())->find($projection->tmdbID);
			array_push($movieArray,$movie);
			
		}
		return $movieArray;
	}

	/** Function which returns movies marked as coming soon
     * @return array[Movie] list of available movies
     */
	private function getComingSoonMovies()
	{
		$movieArray=[];
		$comingSoon = (new ComingSoonModel())->findAll();	

		foreach($comingSoon as $movie)
		{
			$curMovie = (new MovieModel())->find($movie->tmdbID);
			if (!isset($movieArray[$curMovie->tmdbID]))
				$movieArray[$curMovie->tmdbID] = $curMovie;
		}
		return $movieArray;
	}

	public function index()
	{
		$movieArray = $this->getPlayingMovies();	
		return view('index.php',[ 'movies' => $movieArray, 'actMenu' => 1]);
	}

	public function comingSoon()
	{
		$movieArray = $this->getComingSoonMovies();
		return view('index.php',[ 'movies' => $movieArray, 'actMenu' => 2]);
	}

	private function getWantedPlayingMovies($title)
	{
		$movieArray=[];
		$movies = (new MovieModel())
			->like('title',$title, $insensitiveSearch = TRUE)
			->findAll();
		foreach($movies as $movie)
		{
			$curMovie = (new ProjectionModel())->where(['tmdbID' => $movie->tmdbID])->find();
			if (count($curMovie) > 0)
				array_push($movieArray,$movie);
		}
		return $movieArray;
	}

	private function getWantedComingSoonMovies($title)
	{
		$movieArray=[];
		$comingSoon = (new ComingSoonModel())->select('tmdbID')->groupBy('tmdbID')->findAll();	
		
		foreach($comingSoon as $movie)
		{
			$projection = (new ProjectionModel())->where('tmdbID',$movie->tmdbID)->findAll();
			if (count($projection) == 0)
			{
				$curMovie = (new MovieModel())->like('title',$title, $insensitiveSearch = TRUE)->find($movie->tmdbID);
				if (!is_null($curMovie))
					array_push($movieArray,$curMovie);
			}
		}
	
		return $movieArray;
	}

	public function titleSearch()
	{
		$menu = $_GET['actMenu'];
		unset($_GET['actMenu']);
		$title = $_GET['title'];
		unset($_GET['title']);

		if(strcmp($menu,"1") == 0)
		{
			$movieArray = $this->getWantedPlayingMovies($title);
		}
		else
		{
			$movieArray = $this->getWantedComingSoonMovies($title);
		}
		
		return json_encode($movieArray);
		//return view('index.php',[ 'movies' => $movieArray, 'actMenu' => $menu]);
	}

	public function cinemas()
	{
		$cinemaArray = (new CinemaModel())->where(['approved' => 1])->findAll();	
		return view('index.php',[ 'movies' => $cinemaArray, 'cinMenu' => 1]);
	}

	//connect to a database
	/*$db = \Config\Database::connect());
	$db->query('select * from Admins');
	$sql = "select * from Admins where email LIKE :email: AND firstName LIKE :firstName:";
	$db->query($sql,['email' => 'adas', 'firstName' => 'asdas']);
	$db->error();
	prepar(query) pa db->is_executable
	zatvori svaki prepare

	$builder = $model->builder();
	QueryBuilder!
	insert,update, save-radi isto sto i dve prve fje zajedno, delete


	getResult() za uz foreach($query->getResult())
	getRow(numRow);
	$db->countAll();
	*/

}
