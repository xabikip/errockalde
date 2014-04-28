<?php

class TipoEventoView {

    public function agregar() {
        $form = new WebForm('/eventos/tipoevento/guardar');
        $form->add_text('denominacion', 'Tipo evento');
        $form->add_submit('Agregar tipo de evento');
        $str = $form->show();
        print Template('Agregar TipoEvento')->show($str);
    }

    public function editar($obj=array()) {
        $str = file_get_contents(
            STATIC_DIR . "html/eventos/tipoevento_editar.html");
        $html = Template($str)->render($obj);
        print Template('Editar TipoEvento')->show($html);
    }

    public function listar($coleccion=array()) {
        $str = file_get_contents(
            STATIC_DIR . "html/eventos/tipoevento_listar.html");
        $html = Template($str)->render_regex('LISTADO', $coleccion);
        print Template('Listado de TipoEvento')->show($html);
    }
}

?>
