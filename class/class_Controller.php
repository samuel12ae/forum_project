<?php

class Controller {
    private $v; // Instanz View
    private $r; // alle Request ?key=value
    public function __construct() {
        $this->r = $_REQUEST;
        var_dump($this->r); // Test Ansicht
        $this->v = new View();
        switch(array_key_last($this->r)) { //?...&anmeldeformular=true
            case 'anmelden' :
                $this->v->setLayout('anmelden');
                break;
            case 'kontakt' :
                $this->v->setLayout('kontakt');
                break;
            case 'anmeldeformular' :
                $this->setAnmeldung();
                break;
            default : 
                $this->v->setLayout('startseite');
        }
        // letzte Aktion
        $this->v->toDisplay();
    }

    private function setAnmeldung() {
        $id = Model::getIdFromName($this->r['name']);
        if($id) {
            // Fehlermeldung User existiert schon
            $this->info('Bitte anderen Namen wählen', 'red');
        } else {
            // User kann eingetragen werden
            $hash = hash('sha512', $this->r['pass']); // sicherer Abspeichern
            Model::setUserIntoDB($this->r['name'], $this->r['e_mail'], $hash);
            $this->info('Anmeldung erfolgreich', 'green');
        }
        $this->v->setLayout('anmelden'); //welche Seite soll gezeigt werden
    }

    private function info($text, $color) {
        $html = '<div style="background:'.$color.';color=white;';
        $html .= 'display:inline-block;">';
        $html .= $text;
        $html .= '</div>';
        $_SESSION['info'] = $html; // in die Session schreiben 
    }

}

?>