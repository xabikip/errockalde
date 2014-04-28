<?php

class TaldeView {

    public function agregar($errores = array()) {
        $form = new WebForm('/bazkideak/talde/guardar');
        $form->add_text('izena', 'izena', @$_POST['izena']);
        $form->add_text('web', 'Web orria', @$_POST['web']);
        $form->add_text('emaila', 'emaila', @$_POST['emaila']);
        $form->add_text('telefonoa', 'telefonoa', @$_POST['telefonoa']);
        $form->add_submit('Taldea gehitu');
        $form->add_error_zone($errores);
        print Template('Talde berria')->show($form->show());
    }

    public function editar($obj=array()) {
        $form = new WebForm('/bazkideak/talde/guardar');
        $form->add_hidden('id', $obj->MODELO_id);
        # ...
        $form->add_submit('Agregar');
        print Template('Editar Talde')->show($form->show());
    }

    public function listar($coleccion=array()) {
        $str = CollectorViewer($coleccion, 'bazkideak', 'talde',
            false, True, True)->get_table();
        print Template('Listado de Talde')->show($str);
    }
}

?>
