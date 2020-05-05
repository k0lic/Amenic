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
        ackgroundImg
        imdbRating
        imdbID
        reviews
        trailer
    */    
   
    public function toString()
    {
        return "Movie: ".$this->tmdbID."\nTitle: ".$this->title."\nReleased: ".$this->released."\nRuntime: ".$this->runtime;
    }
}

