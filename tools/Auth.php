<?php

namespace Tools;

/**
 * The Auth class (Authentiation) is a helper
 * which makes it easier to deal with users
 * that have been signed in.
 *
 * This object implements the singleton pattern
 * which implies that it can only be instantiated
 * once.
 */
class Auth {

    private function __construct() { }

    private static $auth = null;

    public static function getSingleton() {
        if (is_null(Auth::$auth)) {
            Auth::$auth = new Auth();
        }
        return Auth::$auth;
    }

    public function __get($name) {
        if (!isset($_SESSION['auth'])) {
            return null;
        }

        if (array_key_exists($name, $_SESSION['auth'])) {
            return $_SESSION['auth'][$name];
        }

        $trace = debug_backtrace();
        trigger_error(
            'Undefined property via __get(): ' . $name .
            ' in ' . $trace[0]['file'] .
            ' on line ' . $trace[0]['line'],
            E_auth_NOTICE);

        return null;
    }

    public function __isset($name) {
        if (!isset($_SESSION['auth']))
            return false;
        if (is_null($_SESSION['auth']))
            return false;
        return array_key_exists($name, $_SESSION['auth']);
    }

    public function user() {
        if (self::$auth->isLoggedIn())
            return $_SESSION['auth'];
        else
            return null;
    }

    public function isLoggedIn() {
        return isset($_SESSION['auth']);
    }

    public function logIn($auth) {
        $_SESSION['auth'] = $auth;
    }

    public function logOut() {
        unset($_SESSION['auth']);
    }
}
