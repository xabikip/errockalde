<?php

class BazkideView {

    public function agregar($errores=array()) {

        $form = new WebForm('/bazkideak/bazkide/guardar');
        $form->add_text('izena','izena', @$_POST['izena']);
        $form->add_text('abizena', 'abizena', @$_POST['abizena']);
        $form->add_text('goitizena', 'goitizena', @$_POST['goitizena']);
        $form->add_text('emaila', 'emaila', @$_POST['emaila']);
        $form->add_text('telefonoa', 'telefonoa', @$_POST['telefonoa']);
        $form->add_submit('Bazkidea gehitu');
        $form->add_error_zone($errores);
        print Template('Bazkide berria')->show($form->show());
    }

    public function editar($obj=array(), $errores=array()) {
        $form = new WebForm('/bazkideak/bazkide/guardar');
        $form->add_hidden('id', $obj->bazkide_id);
        $form->add_text('izena','izena', $obj->izena);
        $form->add_text('abizena', 'abizena', $obj->abizena);
        $form->add_text('goitizena', 'goitizena', $obj->goitizena);
        $form->add_text('emaila', 'emaila', $obj->emaila);
        $form->add_text('telefonoa', 'telefonoa', $obj->telefonoa);
        $form->add_submit('Aldaketak gorde');
        $form->add_error_zone($errores);
        print Template('Bazkideta editatu')->show($form->show());
    }

    public function listar($coleccion=array()) {
        $str = CollectorViewer($coleccion, 'bazkideak', 'bazkide',
            False, True, True)->get_table();
        print Template('Bazkide zerrenda')->show($str);
    }
}

?>
