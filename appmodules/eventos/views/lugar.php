<?php

class LugarView { 

    public function agregar() {
        $str = file_get_contents(
            STATIC_DIR . "html/eventos/lugar_agregar.html");
        print Template('Agregar Lugar')->show($str);
    }
    
    public function editar($obj=array()) {
        $str = file_get_contents(
            STATIC_DIR . "html/eventos/lugar_editar.html");
        $html = Template($str)->render($obj);
        print Template('Editar Lugar')->show($html);
    }

    public function listar($coleccion=array()) {
        $str = file_get_contents(
            STATIC_DIR . "html/eventos/lugar_listar.html");
        $html = Template($str)->render_regex('LISTADO', $coleccion);
        print Template('Listado de Lugar')->show($html);
    }
}

?>
