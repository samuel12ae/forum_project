<?php

class View {
    private $out; // Ausgabe
    public function setLayout($tpl) {
        // Aufbau des Design mit 3 Templates
        ob_start();//PHP einen Puffer
        // auf Server berechnet
        // Template in Reihenfolge
        require_once('tpl/header.tpl.php');
        // immer austauschbar
        require_once('tpl/'.$tpl.'.tpl.php');
        require_once('tpl/footer.tpl.php');

        $this->out = ob_get_contents(); // Puffer auslesen
        ob_end_clean(); // Puffer löschen
    }

    public function toDisplay() {
        echo $this->out;
    }

}

?>