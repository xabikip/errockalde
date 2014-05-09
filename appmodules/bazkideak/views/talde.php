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

    public function editar($obj=array(), $errores=array()) {
        $form = new WebForm('/bazkideak/talde/guardar');
        $form->add_hidden('id', $obj->talde_id);
        $form->add_text('izena', 'izena', $obj->izena);
        $form->add_text('web', 'Web orria', $obj->web);
        $form->add_text('emaila', 'emaila', $obj->emaila);
        $form->add_text('telefonoa', 'telefonoa', $obj->telefonoa);
        $form->add_submit('Gorde');
        $form->add_error_zone($errores);
        print Template('Taldea editatu')->show($form->show());
    }

    public function listar($coleccion=array()) {
        $str = CollectorViewer($coleccion, 'bazkideak', 'talde',
            false, True, True)->get_table();
        print Template('Taldeen zerrenda')->show($str);
    }
}

?>
