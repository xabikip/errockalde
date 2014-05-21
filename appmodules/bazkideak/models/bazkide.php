<?php

class Bazkide extends StandardObject {

    public function __construct(User $name=NULL) {
        $this->bazkide_id = 0;
        $this->izena = '';
        $this->abizena = '';
        $this->goitizena = '';
        $this->emaila = '';
        $this->telefonoa = '';
        $this->user = $name;
    }

}

?>
