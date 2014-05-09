<?php

class EkitaldiMotaView {

    public function agregar($errores = array()) {
        $form = new WebForm('/ekitaldiak/ekitaldimota/guardar');
        $form->add_text('deitura', 'Ekitaldi mota', @$_POST['deitura']);
        $form->add_submit('Ekitaldi mota gehitu');
        $form->add_error_zone($errores);
        $str = $form->show();
        print Template('Ekitaldi Mota gehitu')->show($str);
    }

    public function editar($obj=array()) {
        $str = file_get_contents(
            STATIC_DIR . "html/ekitaldiak/ekitaldimota_editar.html");
        $html = Template($str)->render($obj);
        print Template('Editar Ekitaldi Mota')->show($html);
    }

    public function listar($coleccion=array()) {
        $tabla = CollectorViewer($coleccion, 'ekitaldiak',  'ekitaldimota',
            False, True, True)->get_table();
        print Template('Ekitaldi moten zerrenda')->show($tabla);
    }
}

?>
