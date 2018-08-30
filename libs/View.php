<?php

/**
 * Standard View
 *
 * @author: Marlon Böhland
 * @access: public
 */
class View
{
    function __construct()
    {

    }

    /**
     * Rendern der Views
     * @param $name
     * @param bool $noInclude
     */
    public function render($name, $noInclude = false)
    {
        require 'views/' . $name . '.php';
    }

    /**
     * @param $alertType = Bootstrap alert Klasse (danger, info, success, warning)
     * @param $option = Spezielle CSS Klasse z. B. zur Anzeige einer Nachricht am unteren Bildschirmrand (fixed)
     * @param $message = Nachricht
     */
    static function showAlert($alertType, $option = null, $message)
    {
        switch ($alertType) {
            case 'success':
                $icon = "glyphicon-ok";
                break;
        }
        echo '<div class="alert alert-' . $alertType . ' ' . $option . '">';
        echo '<div class="col-sm-3"></div>';
        echo '<div class="col-sm-5"><strong><center>' . $message . '</center></strong></div>';
        echo '<div class="clearfix"></div>';
        echo '<div class="col-sm-3"></div>';
        echo '</div>';
        echo '<div class="clearfix"></div>';
    }
}