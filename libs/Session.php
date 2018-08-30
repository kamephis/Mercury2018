<?php

/**
 * Session Management
 *
 * @author: Marlon Böhland
 * @access: public
 */
class Session
{
    // Initialisieren der Session
    public static function init()
    {
        ini_set('session.gc_probability', 1);
        ini_set('session.gc_divisor', 1);
        ini_set('session.gc_maxlifetime', 36000);
        ini_set('session.cache_expire', 36000);
        ini_set('session.cookie_lifetime', 36000);
        ini_set('session.save_path', 'sessions');
        session_start();
    }

    // Setzen von Session Variablen
    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    // Auslesen von Session Variablen
    public static function get($key)
    {
        if (isset($_SESSION[$key])) return $_SESSION[$key];
    }

    // Session zerstören
    public static function destroy()
    {
        session_unset('userName');
        session_destroy();
    }

    // Überprüfen ob ein User eingeloggt ist
    public static function checkAuth()
    {
        $sUID = self::get('UID');
        if (!isset($sUID)) {
            self::destroy();
            // Weiterleitung auf die Login-Seite
            header('location: ' . URL . 'login');
        } else {
            return true;
        }
        return false;
    }
}