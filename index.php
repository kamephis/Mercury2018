<?php
error_reporting(E_ERROR);
$app_url = explode('.', $_SERVER['HTTP_HOST']);
$app = $app_url[0];

//session_start();

// Konfiguration
require 'conf.php';

// Klassen Auto-Loader
function __autoload($class)
{
    require 'libs/' . $class . ".php";
}
$bootstrap = new Bootstrap();

//Pfade (individuell)
//$bootstrap->setControllerPath();
//$bootstrap->setModelPath();
//$bootstrap->setDefaultFile();
//$bootstrap->setErrorFile();

// Anwendung starten
$bootstrap->init();