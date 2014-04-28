<?php

class Evento extends StandardObject {

    public function __construct(TipoEvento $tipo=NULL, Lugar $lugar=NULL) {
        $this->evento_id = 0;
        $this->tipoevento = $tipo; // composición
        $this->nombre = '';
        $this->fecha = '';
        $this->hora = '';
        $this->lugar = $lugar;
    }

}

?>
