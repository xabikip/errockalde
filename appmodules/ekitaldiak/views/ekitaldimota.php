<?php

class EkitaldiMotaView {

    public function agregar($errores = array()) {
        $form = new WebForm('/ekitaldiak/ekitaldimota/guardar');
        $form->add_text('deitura', 'Ekitaldi mota', @$_POST['deitura']);
        $form->add_submit('Ekitaldi mota gehitu');
        $form->add_error_zone($errores);
        print Template('Ekitaldi Mota gehitu')->show($form->show());
    }

    public function editar($obj=array(), $errores=array()) {
        $form = new WebForm('/ekitaldiak/ekitaldimota/guardar');
        $form->add_hidden('id', $obj->ekitaldimota_id);
        $form->add_text('deitura', 'Ekitaldi mota', $obj->deitura);
        $form->add_submit('Aldaketak gorde');
        $form->add_error_zone($errores);
        print Template('Ekitaldi Mota gehitu')->show($form->show());
    }

    public function listar($coleccion=array()) {
        $tabla = new CustomCollectorViewer($coleccion, 'ekitaldiak',  'ekitaldimota',
            False, True, True);
        print Template('Ekitaldi moten zerrenda')->show($tabla->get_table());
    }

    public function eventos_a_borrar($ekitaldiak=array()) {

        $plantilla = file_get_contents( STATIC_DIR . 'html/back/ekitaldiak/ezabatu_mezua.html');

        $render_ekitaldiak = Template($plantilla)->render_regex('EKITALDIAK', $ekitaldiak);
        print Template()->show($render_ekitaldiak);
    }
}

?>
