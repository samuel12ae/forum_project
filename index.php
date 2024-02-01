<?php

session_start(); // Coockie beim User(Browser) File auf servr
$_SESSION['info'] = ''; // Fehler und Informationen

require_once('php/db_init.php'); //Initialisierung

spl_autoload_register(function ($classname){
    //Klasse laden class/class_Controller.php
    require_once('class/class_'.$classname.'.php');
});

//Objectaufruf zu einer Klasse
new Controller();

?>