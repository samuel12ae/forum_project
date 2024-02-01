<?php

class Service {

    private static $myPDO;
    // Datenbank verbindung
    private static function dbConnect() {
        self::$myPDO = new PDO('mysql:host='.INI['HOST'].';dbname='.INI['DB'].';charset=utf8', INI['USER'], INI['PASS']);
    }
    // SQL Injektion Sicherheit
    public static function setPrepare($sql) {
        self::dbConnect(); // Datenbankverbindung herstellen
        return self::$myPDO->prepare($sql); // Instanz des PDO Object (Puffer auf Server)
    }

    // 1 Wert aus DB ermitteln
    public static function getOneFromDB($mask) {
        $mask->execute(); // SQL Injection sichere Ausführung
        return $mask->fetchColumn(); // liefert genau einen Wert String, int, boolean
        
    }

    // in DB schreiben
    public static function setIntoDB($mask) {
        return $mask->execute();
    }
    // insertDB
    // getArrayFromDB




}
?>