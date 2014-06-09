<?php

class CustomURLTalde extends StandardObject {

    function __construct(Talde $talde=NULL) {
        $this->customurltalde_id = 0;
        $this->deitura = '';
        $this->talde = $talde;
    }

}

?>
