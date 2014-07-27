<?php

class post extends StandardObject {

    public function __construct(kategoria $kategoria=NULL) {
        $this->post_id = 0;
        $this->titularra = '';
        $this->sortua = '';
        $this->aldatua = '';
        $this->kategoria = $kategoria;

    }

}

?>
