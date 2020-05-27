<?php namespace App\Entities;

/*

    Author: Martin Mitrović
    Github: Rpsaman13000

*/

use CodeIgniter\Entity;

/** Movie - object of this class represents one row in Movies table
 *  @version 1.0
 */
class Movie extends Entity
{
    /*
        Columns:
         -tmdbID
         -title
         -released
         -runtime
         -genre
         -director
         -writer
         -actors
         -plot
         -poster
         -backgroundImg
         -imdbRating
         -imdbID
         -reviews
         -trailer
    */    
   
    public function toString()
    {
        return "Movie<br/><br/>tmdbID: ".$this->tmdbID."<br/>Title: ".$this->title."<br/>Released: ".$this->released."<br/>Runtime: ".$this->runtime.
        "<br/>Genre: ".$this->genre."<br/>Director: ".$this->director."<br/>Writer: ".$this->writer."<br/>Actor: ".$this->actor.
        "<br/>Plot: ".$this->plot."<br/>Poster: ".$this->poster."<br/>Background: ".$this->backgroundImg."<br/>imdbRating: ".$this->imdbRating.
        "<br/>ImdbID: ".$this->imdbID."<br/>Reviews: ".$this->reviews."<br/>Trailer: ".$this->trailer;
    }
}

