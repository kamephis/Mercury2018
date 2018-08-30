<?php

/**
 * Gemeinsam genutzte Datenbankfunktionen
 *
 * @author: Marlon Böhland
 * @access: public
 * @date: 01.12.2016
 */
class Error extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public static function getError($errorCode)
    {
        switch ($errorCode) {
            case '401':
                //header('location '.URL.'login');
                echo "Zugriff verweigert.";
                break;
        }
    }


}