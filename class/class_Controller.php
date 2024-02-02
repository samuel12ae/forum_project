<?php

class Controller
{
    private $v; // Instanz View
    private $r; // alle Request ?key=value
    public function __construct()
    {
        $this->r = $_REQUEST;
        var_dump($this->r); // Test Ansicht
        $this->v = new View();
        switch (array_key_last($this->r)) { //?...&anmeldeformular=true
            case 'login' :
                $this->checkLogin();
                break;
            case 'veri':
                $this->activateUser();
                break;
            case 'anmelden':
                $this->v->setLayout('anmelden');
                break;
            case 'kontakt':
                $this->v->setLayout('kontakt');
                break;
            case 'anmeldeformular':
                $this->setAnmeldung();
                break;
            default:
                $this->v->setLayout('startseite');
        }
        // letzte Aktion
        $this->v->toDisplay();
    }

    private function checkLogin() {
        $data = Model::getUserData($this->r['e_mail'], hash('sha512', $this->r['pass']));
        echo 'check'.print_r($data);
        // hier Session mit Id gespeichert
    }

    private function activateUser()
    {
        $hash = $this->r['veri']; //id+email
        //prüfe die DB ob id und email existieren
        $array = Model::getAllIdAndEmail();
        $id = false; // keine 
        foreach ($array as $spalte) {
            // vergleiche id+email
            if ($hash == hash('sha512', $spalte['id'] . $spalte['e_mail'])) {
                $id = $spalte['id']; // User verifiziert
                break;
            }
        }
        if ($id) {
            Model::setUserStatus($id, 3);
            $this->info('Verifikation erfolgreich', 'green');
        } else {
            $this->info('Verifikation fehlerhaft', 'red');
        }
        // jetzt id nehmen und neue Status
        $this->v->setLayout('verifikation');
    }

    private function setAnmeldung()
    {
        $id = Model::getIdFromName($this->r['name']);
        if ($id) {
            // Fehlermeldung User existiert schon
            $this->info('Bitte anderen Namen wählen', 'red');
            $this->v->setLayout('anmelden');
            return false;
        }

        // Passwort Komlexitätstest
        if ($this->getKomplex($this->r['pass']) === false) {
            $this->info('Komplexitätsanforderungen nicht erfüllt', 'red');
            $this->v->setLayout('anmelden');
            return false; // weiteres Script wird in dieser Methode nicht ausgeführt
        }

        $id = Model::getIdFromEmail($this->r['e_mail']);
        if ($id) {
            // Fehlermeldung E-mail existiert schon
            $this->info('E-Mail existiert bereits', 'red');
            $this->v->setLayout('anmelden');
            return false;
        }

        //Valide E-mail
        if (!filter_var($this->r['e_mail'], FILTER_VALIDATE_EMAIL)) {
            $this->info('E-Mail nicht korrekt', 'red');
            $this->v->setLayout('anmelden');
            return false;
        }

        // Erfolgreich anmelden
        $hash = hash('sha512', $this->r['pass']); // sicherer Abspeichern
        Model::setUserIntoDB($this->r['name'], $this->r['e_mail'], $hash); // neue id wird erstellt
        $id = Model::getIdFromEmail($this->r['e_mail']); // id zur E-Mail
        $this->setEmailVerification(hash('sha512', $id . $this->r['e_mail']));
        $this->info('Anmeldung erfolgreich', 'green');
        $this->v->setLayout('anmelden'); //welche Seite soll gezeigt werden
        return true;
    }

    public function setEmailVerification($vericode)
    {
        $to = $this->r['e_mail']; //Zieladresse
        $subject = 'Betreff Ihrer anmeldung'; // Betreff
        $header = 'FROM: info@forum.de'; // E-Mail zugewiesen/bestädigt vom Provider
        $text = "Hallo " . $this->r['name'] . "
                Verifizierung einer E-Mail:\r\n
                Vielen Dank für Ihre Anmeldung,\r\n
                bitte bestädigen Sie Ihre E-Mail Adresse:\r\n
                https://www.forum.de/veri=" . $vericode;

        // funktioniert auf Server nicht localhost
        //mail($to, $subject, $text, $header);

        //Testversion für localhost
        file_put_contents('testmail.txt,', $to . "\r\n" . $subject . "\r\n" . $header . "\r\n" . $text);
    }

    private function getKomplex($pass)
    {
        // assiziatives Array ['L':true, 'G':true, 'K':true, 'S':true, 'I':true] = Länge 5
        $komplex = [];
        if (strlen($pass) >= 10) $komplex['L'] = true;
        for ($i = 0; $i < strlen($pass); $i++) {
            if (ctype_upper($pass[$i])) $komplex['G'] = true;
            if (ctype_lower($pass[$i])) $komplex['K'] = true;
            if (ctype_punct($pass[$i])) $komplex['S'] = true;
            if (ctype_digit($pass[$i])) $komplex['I'] = true;
        }
        if (count($komplex) == 5) return true; // kein weiterer Code
        return false;
    }

    private function info($text, $color)
    {
        $html = '<div style="background:' . $color . ';color=white;';
        $html .= 'display:inline-block;">';
        $html .= $text;
        $html .= '</div>';
        $_SESSION['info'] = $html; // in die Session schreiben 
    }
}
