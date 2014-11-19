<?php

class Talde extends StandardObject {

    public function __construct() {
        $this->talde_id = 0;
        $this->izena = '';
        $this->web = '';
        $this->emaila = '';
        $this->telefonoa = '';
        $this->deskribapena = '';
        $this->customurl = '';
        $this->diskoa_collection = array();
    }

    public function add_diskoa(Diskoa $obj) {
        $this->diskoa_collection[] = $obj;
    }

}

?>
