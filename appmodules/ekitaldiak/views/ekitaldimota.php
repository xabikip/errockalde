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
        $tabla = CollectorViewer($coleccion, 'ekitaldiak',  'ekitaldimota',
            False, True, True)->get_table();
        print Template('Ekitaldi moten zerrenda')->show($tabla);
    }
}

?>
