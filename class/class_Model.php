<?php

class Model {
    public static function getIdFromName($name) {
        // SQL Iniection sicher
        $mask = SERVICE::setPrepare('SELECT id FROM tb_user WHERE name = ?'); // in Zwieschenspeicher legen
        $mask->bindValue(1, $name, PDO::PARAM_STR);
        return SERVICE::getOneFromDB($mask);
    }

    public static function getIdFromEmail($e_mail) {
        // SQL Iniection sicher
        $mask = SERVICE::setPrepare('SELECT id FROM tb_user WHERE e_mail = ?'); // in Zwieschenspeicher legen
        $mask->bindValue(1, $e_mail, PDO::PARAM_STR);
        return SERVICE::getOneFromDB($mask);
    }
    
    public static function setUserIntoDB($name, $e_mail, $pass) {
        // status = 4 = User? (nicht verifiziert)
        $mask = SERVICE::setPrepare('INSERT INTO tb_user (name, pass, e_mail, id_status)
                                    VALUES (?, ?, ?, ?)');
        $mask->bindValue(1, $name, PDO::PARAM_STR);
        $mask->bindValue(2, $pass, PDO::PARAM_STR);
        $mask->bindValue(3, $e_mail, PDO::PARAM_STR);
        $mask->bindValue(4, 4, PDO::PARAM_INT); // hardcodiert
        return SERVICE::setIntoDB($mask);
    }

}

?>