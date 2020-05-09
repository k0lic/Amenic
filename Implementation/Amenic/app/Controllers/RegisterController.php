<?php namespace App\Controllers;

/*
    Author: Miloš Živkovic
    Handle: zivkovicmilos
*/

class RegisterController extends BaseController {

	public function index() {	
		return view('/Registration/register.php',[]);
	}

}
