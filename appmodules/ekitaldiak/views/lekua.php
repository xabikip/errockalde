<?php

class LekuaView { 

    public function agregar() {
        $str = file_get_contents(
            STATIC_DIR . "html/ekitaldiak/lekua_agregar.html");
        print Template('Agregar Lekua')->show($str);
    }
    
    public function editar($obj=array()) {
        $str = file_get_contents(
            STATIC_DIR . "html/ekitaldiak/lekua_editar.html");
        $html = Template($str)->render($obj);
        print Template('Editar Lekua')->show($html);
    }

    public function listar($coleccion=array()) {
        $str = file_get_contents(
            STATIC_DIR . "html/ekitaldiak/lekua_listar.html");
        $html = Template($str)->render_regex('LISTADO', $coleccion);
        print Template('Listado de Lekua')->show($html);
    }
}

?>
