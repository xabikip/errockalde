<?php

class EventoView { 

    public function agregar() {
        $str = file_get_contents(
            STATIC_DIR . "html/eventos/evento_agregar.html");
        print Template('Agregar Evento')->show($str);
    }
    
    public function editar($obj=array()) {
        $str = file_get_contents(
            STATIC_DIR . "html/eventos/evento_editar.html");
        $html = Template($str)->render($obj);
        print Template('Editar Evento')->show($html);
    }

    public function listar($coleccion=array()) {
        $str = file_get_contents(
            STATIC_DIR . "html/eventos/evento_listar.html");
        $html = Template($str)->render_regex('LISTADO', $coleccion);
        print Template('Listado de Evento')->show($html);
    }
}

?>
