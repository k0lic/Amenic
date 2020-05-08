<?php namespace App\Controllers;

use App\Models\MovieModel;
use App\Models\ComingSoonModel;
use App\Models\CinemaModel;

class HomeController extends BaseController
{
	private function getPlayingMovies()
	{
		$movieArray=[];
		$movies = (new MovieModel())->findAll();
		foreach($movies as $movie)
		{
			$curMovie = (new ComingSoonModel())->where(['tmdbID' => $movie->tmdbID])->find();
			if (count($curMovie)==0)
			{
				array_push($movieArray,$movie);
			}
		}
		return $movieArray;
	}

	private function getComingSoonMovies()
	{
		$movieArray=[];
		$comingSoon = (new ComingSoonModel())->findAll();	
		foreach($comingSoon as $movie)
		{
			$curMovie = (new MovieModel())->find($movie->tmdbID);
			array_push($movieArray,$curMovie);
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
		$movies = (new MovieModel())->like('title',$title, $insensitiveSearch = TRUE)->findAll();
		foreach($movies as $movie)
		{
			$curMovie = (new ComingSoonModel())->where(['tmdbID' => $movie->tmdbID])->find();
			if (is_null($curMovie))
			{
				array_push($movieArray,$movie);
			}
		}
		return $movieArray;
	}

	private function getWantedComingSoonMovies($title)
	{
		$movieArray=[];
		$comingSoon = (new ComingSoonModel())->findAll();	
		foreach($comingSoon as $movie)
		{
			$curMovie = (new MovieModel())->like('title',$title, $insensitiveSearch = TRUE)->find($movie->tmdbID);
			if (!is_null($curMovie))
				array_push($movieArray,$curMovie);
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
		
		return view('index.php',[ 'movies' => $movieArray, 'actMenu' => $menu]);
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
