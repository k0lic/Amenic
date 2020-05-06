<?php namespace App\Controllers;

use App\Libraries\APIlib;
use App\Entities\Movie;
use App\Models\MovieModel;

class AddMovieToDatabaseController extends BaseController
{
	public function index()
	{
		if(isset($_POST['movieTitle']))
		{
			$apilib = new APIlib();
			$params = $apilib->getMovies($_POST['movieTitle']);
			
			unset($_POST['movieTitle']);
			echo "Results:<br/><br/>";
			foreach($params[2]["results"] as $movie)
			{
				$rDate = "x";
				if (isset($movie['release_date']))
				{
					$rDate =  $movie['release_date'];
				}
				echo "TmdbID: ".$movie['id']." - Title:".$movie['title']." - Released: ".$rDate."<br/>";
			}
			echo "<br/>";
			echo view('ChooseMovieToAdd.php',[ 'params' => $params]);
		}
		else
		{
			echo view('AddMovieForm.php');
		}
	}

	public function addMovie()
	{
		$tmdbID = $_POST['movieID'];
		
		$apilib = new APIlib();
		$movieBasicInfo = $apilib->getMovieBasic($tmdbID);
		$movieCredits = $apilib->getMovieCredits($tmdbID);
		$movieVideos = $apilib->getMovieVideos($tmdbID);
		$movieReviews = $apilib->getMovieReviews($tmdbID);
		//provera na gresku
		
		$title = $movieBasicInfo[2]['title'];
		$release = $movieBasicInfo[2]['release_date'];
		$runtime = $movieBasicInfo[2]['runtime'];
		$genres="";
		foreach($movieBasicInfo[2]['genres'] as $genre)
			$genres = $genres.$genre['name'].", ";
		$genres = substr ( $genres , 0, strlen($genres)-2 );
		
		$directors="";
		$writers="";
		$actors="";
		$numOfActors=0;
		foreach($movieCredits[2]['crew'] as $crew)
		{
			if(strcmp($crew['job'],"Director") == 0)
				$directors=$directors.$crew['name'].", ";
			if(strcmp($crew['job'],"Writer") == 0 ||
				strcmp($crew['job'],"Screenplay") == 0 ||
				strcmp($crew['job'],"Characters") == 0)
				$writers=$writers.$crew['name'].", ";
		}
		foreach($movieCredits[2]['cast'] as $cast)
		{
			if($numOfActors < 6)
			{
				$actors=$actors.$cast['name'].", ";
				$numOfActors++;
			}
			else break;
		}
		$directors = substr($directors,0,strlen($directors)-2 );
		$writers = substr ( $writers , 0, strlen($writers)-2 );
		$actors = substr ( $actors , 0, strlen($actors)-2 );

		$plot =  $movieBasicInfo[2]['overview'];
		$poster = "https://image.tmdb.org/t/p/original".$movieBasicInfo[2]['poster_path'];
	
		$backgroundImg = "https://image.tmdb.org/t/p/original".$movieBasicInfo[2]['backdrop_path'];
	
		$imdbID = $movieBasicInfo[2]['imdb_id'];

		$movieOMDBInfo = $apilib->getMovieInfoOMDB($imdbID);
		$imdbRating = $movieOMDBInfo[2];
		if (isset($imdbRating['Ratings']) && count($imdbRating['Ratings']) > 0)
		{
			$imdbRating = $imdbRating['Ratings'][0]['Value'];
		}
		else
			$imdbRating = "";
		$imdbRating = substr($imdbRating, 0, strpos($imdbRating,"/"));

		$reviews="";
		$numOfReviews=0;
		foreach($movieReviews[2]['results'] as $review)
		{
			if($numOfReviews < 10)
			{
				$reviews=$reviews.$review['author']." - ".$review['content'].", ";
				$numOfReviews++;
			}
			else break;
		}
		$reviews = substr ( $reviews , 0, strlen($reviews)-2 );
		
		$trailer="";
		foreach($movieVideos[2]['results'] as $video)
			if (strcmp($video['type'], 'Trailer') == 0) {
				$trailer="https://www.youtube.com/watch?v=".$video['key'];
				break;
		}
		
		$movie = new Movie([
			'tmdbID' => $tmdbID,
			'title' => $title,
			'released' => $release,
			'runtime' => $runtime,
			'genre' => $genres,
			'director' => $directors,
			'writer' => $writers,
			'actors' => $actors,
			'plot' => $plot,
			'poster' => $poster,
			'backgroundImg' =>  $backgroundImg,
			'imdbRating' => $imdbRating,
			'imdbID' => $imdbID,
			'reviews' => $reviews,
			'trailer' => $trailer]);

		$movieModel = new MovieModel();
		if ($movieModel->insert($movie)) {
			echo 'failed<br/>'.$movieModel->errors();
		  } 
		
		echo $movie->toString();
		echo "<br/><a href=\"/AddMovieToDatabaseController\">Dodaj jos jedan film!</a><br/>";
		echo "<br/><a href=\"/HomeController\">Nazad na glavnu stranu!</a><br/>";
	}
}

// sta ako jos uvek nije uradjen rating, release date