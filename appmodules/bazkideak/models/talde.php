<?php

class Talde extends StandardObject {

    public function __construct() {
        $this->talde_id = 0;
        $this->izena = '';
        $this->web = '';
        $this->emaila = '';
        $this->telefonoa = '';
        $this->deskribapena = '';
        $this->bazkide_collection = array();
    }

    public function add_bazkide(Bazkide $obj) {
        $this->bazkide_collection[] = $obj;
    }

}

?>
