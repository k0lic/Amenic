<?php namespace App\Libraries;

use GuzzleHttp\Client;

/** APIlib – biblioteka koja dohvata podatke sa TMDB apija i vraca json format
 *  @version 1.0
 */

class APIlib {

    /** Funkcija koja dohvata osnovne podatke o filmu na osnovu njegovog TMDBid polja
     * @param string $movieID id filma u TMDB bazi filmova
     * @return array $params [$statusniKod, $tipFajla, $jsonObjekat]
     */
        public function getMovieBasic($movieID)
        {
            $client = new Client();

            $res = $client->request('GET', 'https://api.themoviedb.org/3/movie/'.$movieID.'?api_key=a447e93ca55c73e315f16a4930488fcf', [
                'auth' => ['', '']
            ]);
            
            $params[0] =  $res->getStatusCode(); //200 is ok
            $params[1] =  $res->getHeader('content-type')[0]; //is application/json?
            $params[2] =  \GuzzleHttp\json_decode($res->getBody()->getContents(),true);//json
            
            return $params;
        }
    
    /** Funkcija koja dohvata podatke o svim ljudima koji su radili na filmu i njihovim ulogama
     * @param string $movieID id filma u TMDB bazi filmova
     * @return array $params [$statusniKod, $tipFajla, $jsonObjekat]
     */
        public function getMovieCredits($movieID)
        {
            $client = new Client();

            $res = $client->request('GET', 'https://api.themoviedb.org/3/movie/'.$movieID.'/credits?api_key=a447e93ca55c73e315f16a4930488fcf', [
                'auth' => ['', '']
            ]);
            
            $params[0] =  $res->getStatusCode(); //200 is ok
            $params[1] =  $res->getHeader('content-type')[0]; //is application/json?
            $params[2] =  \GuzzleHttp\json_decode($res->getBody(),true); //json
            
            return $params;
        }

    /** Funkcija koja dohvata podatke o videima koji su vezani za zadati film
     * @param string $movieID id filma u TMDB bazi filmova
     * @return array $params [$statusniKod, $tipFajla, $jsonObjekat]
     */
        public function getMovieVideos($movieID)
        {
            $client = new Client();

            $res = $client->request('GET', 'https://api.themoviedb.org/3/movie/'.$movieID.'/videos?api_key=a447e93ca55c73e315f16a4930488fcf', [
                'auth' => ['', '']
            ]);
            
            $params[0] =  $res->getStatusCode(); //200 is ok
            $params[1] =  $res->getHeader('content-type')[0]; //is application/json?
            $params[2] =  \GuzzleHttp\json_decode($res->getBody(),true); //json
            
            return $params;
        }

     /** Funkcija koja dohvata neke komentare za zadati film
     * @param string $movieID id filma u TMDB bazi filmova
     * @return array $params [$statusniKod, $tipFajla, $jsonObjekat]
     */
        public function getMovieReviews($movieID)
        {
            $client = new Client();

            $res = $client->request('GET', 'https://api.themoviedb.org/3/movie/'.$movieID.'/reviews?api_key=a447e93ca55c73e315f16a4930488fcf', [
                'auth' => ['', '']
            ]);
            
            $params[0] =  $res->getStatusCode(); //200 is ok
            $params[1] =  $res->getHeader('content-type')[0]; //is application/json?
            $params[2] =  \GuzzleHttp\json_decode($res->getBody(),true); //json
            
            return $params;
        }

    /** Funkcija koja dohvata sve filmove koji u naslovu imaju zadat parametar
     * @param string $movieTitle naslov ili deo naslova filma koji se trazi
     * @return array $params [$statusniKod, $tipFajla, $jsonObjekat]
     */
        public function getMovies($movieTitle)
        {
            $client = new Client();
            
            $res = $client->request('GET', 'https://api.themoviedb.org/3/search/movie?api_key=a447e93ca55c73e315f16a4930488fcf&language=en-US&page=1&include_adult=false&query='.$movieTitle, [
                'auth' => ['', '']
            ]);
            
            $params[0] =  $res->getStatusCode(); //200 is ok
            $params[1] =  $res->getHeader('content-type')[0]; //is application/json?
            $params[2] =  \GuzzleHttp\json_decode($res->getBody(),true); //json
            
            return $params;
        }

    /** Funkcija koja dohvata neke parametre o zadatom filmu sa OMDBja
     * @param string $movieID IMDB id za zeljeni film
     * @return array $params [$statusniKod, $tipFajla, $jsonObjekat]
     */
        public function getMovieInfoOMDB($movieID)
        {
            $client = new Client();
             
            $res = $client->request('GET', 'http://www.omdbapi.com/?apikey=eec78257&i='.$movieID, [
                'auth' => ['', '']
            ]);
            
            $params[0] =  $res->getStatusCode(); //200 is ok
            $params[1] =  $res->getHeader('content-type')[0]; //is application/json?
            $params[2] =  \GuzzleHttp\json_decode($res->getBody(),true); //json
            
            return $params;
        }
}