<?php namespace App\Helpers;

/*
    Author: Miloš Živkovic
    Github: zivkovicmilos
*/

use Exception;
use \Firebase\JWT\JWT;

if(!function_exists('isValid')) {

    /**
     *  Decodes the token and checks its validity
     * 
     *  @param string token
     * 
     *  @return bool|object
     */
    function isValid($token) {

        // Validate the token, if it's not valid return false
        // otherwise, return an object with fields firstName, lastName, email and type

        $decoded = null;
        try {
            $decoded = JWT::decode($token, base64_decode(strtr(jwtSecret, '-_', '+/')), ['HS256']);
        } catch (Exception $e) {}
    
        return is_null($decoded)?false:$decoded;
    }

}

if(!function_exists('isAuthenticated')) {
    
    /**
     *  Checks if the user is of a certain type
     * 
     *  @param string type
     * 
     *  @return bool
     */
    function isAuthenticated($type) {
        if(isset($_COOKIE['token'])) {

            $ret = isValid($_COOKIE['token']);
            if($ret == false) return false;

            return $ret->type == $type;
        }
    
        return false;
    }

}

if(!function_exists('setToken')) {

    /**
     *  Sets the token to a cookie
     * 
     *  @param string token
     * 
     *  @return void
     */
    function setToken($token) {
        wipeToken();
    
        if(isValid($token) != false) {
            // If it's a valid token, set it to the cookie
            // Cookie is valid for 1h
    
            setcookie('token', $token,time()+3600*24,'/');
            $_COOKIE['token'] = $token;
        }
    }

}

if(!function_exists('wipeToken')) {

    /**
     *  Destroys the cookie with the token
     *  
     *  @return void
     */
    function wipeToken() {
        if(isset($_COOKIE['token'])) {
            unset($_COOKIE['token']);
        }
        setcookie('token', null, time() - 3600, '/');
    }

}

if(!function_exists('generateToken')) {

    /**
     *  Generates the JSON Web Token
     * 
     *  @param object payload
     * 
     *  @return string
     */
    function generateToken($payload) {
        return JWT::encode($payload, base64_decode(strtr(jwtSecret, '-_', '+/')), 'HS256');
    }

}

if(!function_exists('deleteCookie')) {

    /**
     *  Delete a specific cookie
     * 
     *  @param string name
     * 
     *  @return void
     */
    function deleteCookie($name) {
        setcookie($name, null, time() - 3600, '/');
        if(isset($_COOKIE[$name])) {
            unset($_COOKIE[$name]);
        }
    }
    
}