<?php namespace App\Helpers;

/*
    Author: Miloš Živkovic
    Github: zivkovicmilos
*/

use Exception;
use \Firebase\JWT\JWT;

if(!function_exists('isValid')) {

    // Validate the token, if it's not valid return false
    // otherwise, return an object with fields firstName, lastName, email and type
    function isValid($token) {

        $decoded = null;
        try {
            $decoded = JWT::decode($token, base64_decode(strtr(jwtSecret, '-_', '+/')), ['HS256']);
        } catch (Exception $e) {}
    
        return is_null($decoded)?false:$decoded;
    }

}

if(!function_exists('isAuthenticated')) {

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

    function setToken($token) {
        wipeToken();
    
        if(isValid($token) != false) {
            // If it's a valid token, set it to the cookie
            // Cookie is valid for 1h
    
            setcookie('token', $token,time()+3600,'/');
            $_COOKIE['token'] = $token;
        }
    }

}

if(!function_exists('wipeToken')) {

    // Destroy the cookie
    function wipeToken() {
        if(isset($_COOKIE['token'])) {
            unset($_COOKIE['token']);
        }
        setcookie('token', null, time() - 3600, '/');
    }

}

if(!function_exists('generateToken')) {

    function generateToken($payload) {
        return JWT::encode($payload, base64_decode(strtr(jwtSecret, '-_', '+/')), 'HS256');
    }

}

if(!function_exists('deleteCookie')) {

    function deleteCookie($name) {
        setcookie($name, null, time() - 3600, '/');
        if(isset($_COOKIE[$name])) {
            unset($_COOKIE[$name]);
        }
    }
    
}