<?php namespace App\Libraries;

/*

    Author: Martin Mitrović
    Github: Rpsaman13000

*/

use function App\Helpers\getReviews;
use GuzzleHttp\Client;

/** APIlib – library used to fetch data from TMDB API and return it in JSON format
 *  @version 1.0
 */

class APIlib {

    /** Fetches primary information for a movie
     * @param string $movieID TMDBid - unique identifier for a movie in TMDB
     * @return array $params [$statusCode, $fileType, $jsonObject]
     */
        public function getMovieBasic($movieID)
        {
            $client = new Client();

            //hardcoding obtained api_key from TMDB
            $res = $client->request('GET', 'https://api.themoviedb.org/3/movie/'.$movieID.'?api_key=a447e93ca55c73e315f16a4930488fcf', [
                'auth' => ['', '']
            ]);
            
            $params[0] =  $res->getStatusCode(); //200 is ok
            $params[1] =  $res->getHeader('content-type')[0]; //is application/json?
            $params[2] =  \GuzzleHttp\json_decode($res->getBody()->getContents(),true);//json
            
            return $params;
        }
    
    /** Fetches cast and crew for a movie
     * @param string $movieID TMDBid - unique identifier for a movie in TMDB
     * @return array $params [$statusCode, $fileType, $jsonObject]
     */
        public function getMovieCredits($movieID)
        {
            $client = new Client();

            //hardcoding obtained api_key from TMDB
            $res = $client->request('GET', 'https://api.themoviedb.org/3/movie/'.$movieID.'/credits?api_key=a447e93ca55c73e315f16a4930488fcf', [
                'auth' => ['', '']
            ]);
            
            $params[0] =  $res->getStatusCode(); //200 is ok
            $params[1] =  $res->getHeader('content-type')[0]; //is application/json?
            $params[2] =  \GuzzleHttp\json_decode($res->getBody(),true); //json
            
            return $params;
        }

    /** Fetches the videos that have been added to a movie
     * @param string $movieID TMDBid - unique identifier for a movie in TMDB
     * @return array $params [$statusCode, $fileType, $jsonObject]
     */
        public function getMovieVideos($movieID)
        {
            $client = new Client();

            //hardcoding obtained api_key from TMDB
            $res = $client->request('GET', 'https://api.themoviedb.org/3/movie/'.$movieID.'/videos?api_key=a447e93ca55c73e315f16a4930488fcf', [
                'auth' => ['', '']
            ]);
            
            $params[0] =  $res->getStatusCode(); //200 is ok
            $params[1] =  $res->getHeader('content-type')[0]; //is application/json?
            $params[2] =  \GuzzleHttp\json_decode($res->getBody(),true); //json
            
            return $params;
        }

     /** Fetches the user reviews for a movie
     * @param string $imdbID IMDBid - unique identifier for a movie in IMDB
     * @return array $params [$statusCode, $fileType, $jsonObject]
     */
        public function getMovieReviews($imdbID)
        {
            helper('imdb_helper');
    
            return getReviews($imdbID);
        }

    /** Fetches all the movies containing the phrase provided
     *  as a parameter to the function in their title
     * @param string $movieTitle title, or part of the title of a movie
     * @return array $params [$statusCode, $fileType, $jsonObject]
     */
        public function getMovies($movieTitle)
        {
            $client = new Client();
            
            //hardcoding obtained api_key from TMDB
            $res = $client->request('GET', 'https://api.themoviedb.org/3/search/movie?api_key=a447e93ca55c73e315f16a4930488fcf&language=en-US&page=1&include_adult=false&query='.$movieTitle, [
                'auth' => ['', '']
            ]);
            
            $params[0] =  $res->getStatusCode(); //200 is ok
            $params[1] =  $res->getHeader('content-type')[0]; //is application/json?
            $params[2] =  \GuzzleHttp\json_decode($res->getBody(),true); //json
            
            return $params;
        }

    /** Fetches the specified page from the results of the TMDB search.
     * @param string $movieTitle - the value which TMDB should use to search movie titles
     * @param string $pageNumber - the number of the requested page
     * @return array $page - the resulting page, containing: the query status code, the type of content, and the content itself
     */
    public function getMoviesPage($movieTitle, $pageNumber)
    {
        $movieTitle = str_replace(" ", "%20", $movieTitle);
        $client = new Client();
        
        //hardcoding obtained api_key from TMDB
        $res = $client->request('GET', 'https://api.themoviedb.org/3/search/movie?api_key=a447e93ca55c73e315f16a4930488fcf&language=en-US&page='.$pageNumber.'&include_adult=false&query='.$movieTitle, [
            'auth' => ['', '']
        ]);
        
        $page["status"] =  $res->getStatusCode(); //200 is ok
        $page["type"] =  $res->getHeader('content-type')[0]; //is application/json?
        $page["body"] =  \GuzzleHttp\json_decode($res->getBody(),true); //json
        
        return $page;
    }

    /** Fetches the data about a movie from OMDB API 
     * @param string $movieID OMDBid - unique identifier for a movie in OMDB
     * @return array $params [$statusCode, $fileType, $jsonObject]
     */
        public function getMovieInfoOMDB($movieID)
        {
            $client = new Client();
             
            //hardcoding obtained api_key from TMDB
            $res = $client->request('GET', 'http://www.omdbapi.com/?apikey=eec78257&i='.$movieID, [
                'auth' => ['', '']
            ]);
            
            $params[0] =  $res->getStatusCode(); //200 is ok
            $params[1] =  $res->getHeader('content-type')[0]; //is application/json?
            $params[2] =  \GuzzleHttp\json_decode($res->getBody(),true); //json
            
            return $params;
        }
}

//it is possible to put all of this functions into one function but it would ruin all the code using this library so it is left for another version of project