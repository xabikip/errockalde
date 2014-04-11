<?php

class EkitaldiMotaView {

    public function agregar() {
        $form = new WebForm('/ekitaldiak/ekitaldimota/guardar');
        $form->add_text('deitura', 'Ekitaldi mota');
        $form->add_submit('Ekitaldi mota gehitu');
        $str = $form->show();
        print Template('EkitaldiMota gehitu')->show($str);
    }

    public function editar($obj=array()) {
        $str = file_get_contents(
            STATIC_DIR . "html/ekitaldiak/ekitaldimota_editar.html");
        $html = Template($str)->render($obj);
        print Template('Editar EkitaldiMota')->show($html);
    }

    public function listar($coleccion=array()) {
        $str = file_get_contents(
            STATIC_DIR . "html/ekitaldiak/ekitaldimota_listar.html");
        $html = Template($str)->render_regex('LISTADO', $coleccion);
        print Template('Listado de EkitaldiMota')->show($html);
    }
}

?>
