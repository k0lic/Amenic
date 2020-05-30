<?php namespace App\Controllers;

/*
    Author: Miloš Živkovic
    Github: zivkovicmilos
*/

use function App\Helpers\wipeToken;

/** Logout – Controller that handles user logout
 * 
 * @version 1.0
 */

class Logout extends BaseController {

    /**
     * Logs the user out (deletes the user token)
     * @return void
     */
    public function index() {
        helper('auth');

        wipeToken();

        header('Location: /');
        exit();
    }

}