<?php namespace App\Entities;

use CodeIgniter\Entity;

class Movie extends Entity
{

    /*
        tmdbID
        title
        released
        runtime
        genre
        director
        writer
        actors
        plot
        poster
        backgroundImg
        imdbRating
        imdbID
        reviews
        trailer
    */    
   
    public function toString()
    {
        return "Movie<br/><br/>tmdbID: ".$this->tmdbID."<br/>Title: ".$this->title."<br/>Released: ".$this->released."<br/>Runtime: ".$this->runtime.
        "<br/>Genre: ".$this->genre."<br/>Director: ".$this->director."<br/>Writer: ".$this->writer."<br/>Actor: ".$this->actor.
        "<br/>Plot: ".$this->plot."<br/>Poster: ".$this->poster."<br/>Background: ".$this->backgroundImg."<br/>imdbRating: ".$this->imdbRating.
        "<br/>ImdbID: ".$this->imdbID."<br/>Reviews: ".$this->reviews."<br/>Trailer: ".$this->trailer;
    }
}

