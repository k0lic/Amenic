<?php namespace App\Controllers;

/*
    Author: Miloš Živkovic
    Github: zivkovicmilos
*/

use function App\Helpers\generateToken;
use function App\Helpers\setToken;
use function App\Helpers\wipeToken;

class Logout extends BaseController {

    public function index() {
        helper('auth');

        wipeToken();

        header('Location: /');
        exit();
    }

}