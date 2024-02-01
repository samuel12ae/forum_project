<?php
$array = parse_ini_file('db.ini');//TRUE
// alle ini Daten als Konstante definieren
define('INI',$array);
echo INI['USER'];//assoziatives Array
// Datenbankschnittstelle
try{
$myPDO = new PDO('mysql:host='.INI['HOST'].';charset=utf8', INI['USER'], INI['PASS']);
} catch (PDOException $e) {
    // Fehlermeldung
    echo 'Verbindung fehlgeschlagen'. $e->getMessage();
}
// Datenbank anlegen
$myPDO->exec('CREATE DATABASE IF NOT EXISTS '.INI['DB']);

// Datenbank zu Verfügung stellen
$myPDO->exec('USE '.INI['DB']);

// Tabellen erstellen
$myPDO->exec('CREATE TABLE IF NOT EXISTS tb_status(
            id INT(11) AUTO_INCREMENT PRIMARY KEY, 
            text VARCHAR(255) NOT NULL UNIQUE
            )');

$myPDO->exec('CREATE TABLE IF NOT EXISTS tb_user(
            id INT(11) AUTO_INCREMENT PRIMARY KEY, 
            name VARCHAR(50) NOT NULL UNIQUE,
            pass VARCHAR(128) NOT NULL,
            e_mail VARCHAR(255) NOT NULL UNIQUE,
            id_status INT(11),
            FOREIGN KEY(id_status) REFERENCES tb_status(id)
            )');

$myPDO->exec('CREATE TABLE IF NOT EXISTS tb_theme(
            id INT(11) AUTO_INCREMENT PRIMARY KEY, 
            text VARCHAR(255) NOT NULL UNIQUE
            )');

$myPDO->exec('CREATE TABLE IF NOT EXISTS tb_post(
            id INT(11) AUTO_INCREMENT PRIMARY KEY, 
            text VARCHAR(255) NOT NULL,
            date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            points INT(11) NULL,
            id_status INT(11) NOT NULL,
            id_user INT(11) NOT NULL,
            id_theme INT(11),
            FOREIGN KEY(id_status)REFERENCES tb_status(id),
            FOREIGN KEY(id_user)REFERENCES tb_user(id),
            FOREIGN KEY(id_theme)REFERENCES tb_theme(id)
            )');

/*status Tabelle*/
$res = $myPDO->query('SELECT count(*) FROM tb_status');
if(!$res->fetchColumn()) {
    $myPDO->exec('INSERT INTO tb_status(text) 
                    VALUES("Admin"), ("Moderator"), ("User"), ("User?"), 
                          ("Beitrag"), ("Kommentar"), ("Blockiert")');
}
//SQL Fehler analysieren
$error = $myPDO->errorInfo(); // SQL Fehlermeldung
echo $error[2]; //Textausgabe

?> 