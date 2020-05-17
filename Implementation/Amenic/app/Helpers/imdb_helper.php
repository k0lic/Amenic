<?php namespace App\Helpers;

/*
    Author: Miloš Živkovic
    Github: zivkovicmilos
*/

use PHPHtmlParser\Dom;

if(!function_exists('getReviews')) {

    function getReviews($imdbID) {

        $dom = new Dom;

        $dom->loadFromUrl("https://www.imdb.com/title/$imdbID/reviews");

        // First review
        $wrapper = $dom->find('.display-name-link')[0];
        if(!is_null($wrapper))
        {
            $child = $wrapper->firstChild();

            $firstAuthor = $child->text; 

            $child = $dom->find('.text')[0];
            $firstAuthorText = $child->text;
        }

        // Second review
        $wrapper = $dom->find('.display-name-link')[1];
        if(!is_null($wrapper))
        {
            $child = $wrapper->firstChild();
            $secondAuthor = $child->text;
            $child = $dom->find('.text')[1];
            $secondAuthorText = $child->text;
        }
        
        $reviews = [
            'firstAuthor' => [
                'name' => (isset($firstAuthor) ? $firstAuthor : "No review available"),
                'text' => (isset($firstAuthorText) ? $firstAuthorText : "")
            ],
            'secondAuthor' => [
                'name' => (isset($secondAuthor) ? $secondAuthor : "No review available"),
                'text' => (isset($secondAuthorText) ? $secondAuthorText : "")
            ]
        ];

        return $reviews;        
    }

}