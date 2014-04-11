<?php

class Ekitaldia extends StandardObject {

    public function __construct(EkitaldiMota $mota=NULL, Lekua $lekua=NULL) {
        $this->ekitaldia_id = 0;
        $this->ekitaldimota = $mota; // composición
        $this->izena = '';
        $this->data = '';
        $this->ordua = '';
        $this->lekua = $lekua;
    }

}

?>
