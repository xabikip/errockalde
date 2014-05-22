<?php

class CustomCollectorViewer extends CollectorViewer {

    function __construct($collection=array(), $modulo='', $modelo='',
            $ver=True, $editar=True, $eliminar=True) {

        parent::__construct($collection, $modulo, $modelo, $ver, $editar, $eliminar);

        $this->table = file_get_contents(
            APP_DIR . "common/custom/customcollectorviewer/customcollectorviewer.html");
        $this->set_buttons();

    }

}

?>