<?php namespace App\Controllers;

use App\Libraries\APIlib;

class AddMovieToDatabaseController extends BaseController
{
	public function index()
	{
		if(isset($_POST['movieTitle']))
		{
			$apilib = new APIlib();
			$params = $apilib->getMovies($_POST['movieTitle']);
			
			unset($_POST['movieTitle']);
			var_dump($params[2]);
			echo view('ChooseMovieToAdd.php',[ 'params' => $params]);
		}
		else
		{
			echo view('AddMovieForm.php');
		}
	}

	public function addMovie()
	{
		$movieID = $_POST['movieID'];
		$apilib = new APIlib();
		$movieBasicInfo = $apilib->getMovieBasic($movieID);
		$movieCredits = $apilib->getMovieCredits($movieID);
		$movieVideos = $apilib->getMovieVideos($movieID);
		$movieReviews = $apilib->getMovieReviews($movieID);
		//provera na gresku
	
		//var_dump($movieBasicInfo);
		echo $movieID;
		echo "<br />";
		echo $movieBasicInfo[2]['original_title'];
		echo "<br />";
		echo $movieBasicInfo[2]['release_date'];
		echo "<br />";
		echo $movieBasicInfo[2]['runtime'];
		echo "<br />";
		$genres="";
		foreach($movieBasicInfo[2]['genres'] as $genre)
			$genres = $genres.$genre['name'].", ";
		$genres = substr ( $genres , 0, strlen($genres)-2 );
		echo $genres;
		echo "<br />";
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
		$directors = substr ( $directors , 0, strlen($directors)-2 );
		$writers = substr ( $writers , 0, strlen($writers)-2 );
		$actors = substr ( $actors , 0, strlen($actors)-2 );
		echo $directors;
		echo "<br />";
		echo $writers;
		echo "<br />";
		echo $actors;
		echo "<br />";
		echo $movieBasicInfo[2]['overview'];
		echo "<br />";
		echo "https://image.tmdb.org/t/p/original".$movieBasicInfo[2]['poster_path'];
		echo "<br />";
		echo "https://image.tmdb.org/t/p/original".$movieBasicInfo[2]['backdrop_path'];
		echo "<br />";
		$movieOMDBInfo = $apilib->getMovieInfoOMDB($movieBasicInfo[2]['imdb_id']);
		$rating = $movieOMDBInfo[2]['Ratings'][0]['Value'];
		$rating = substr($rating, 0, strpos($rating,"/"));
		echo $rating;
		echo "<br />";
		echo $movieBasicInfo[2]['imdb_id'];
		echo "<br />";
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
		echo $reviews;
		echo "<br />";
		$trailer="";
		foreach($movieVideos[2]['results'] as $video)
			if (strcmp($video['type'], 'Trailer') == 0) {
				$trailer="https://www.youtube.com/watch?v=".$video['key'];
				break;
		}
		echo $trailer;
	}
}
