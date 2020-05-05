<?php namespace App\Controllers;

use App\Libraries\APIlib;

class GuzzleTestController extends BaseController
{
	public function index()
	{
        $apilib = new APIlib();
        $params = $apilib->getMovies("Joker");  
        var_dump($params);

		return view('emptyView',[ 'params' => []]);
	}
}
