<?php

class BazkideView {

    public function agregar($errores=array()) {

        $form = new WebFormPRO('/bazkideak/bazkide/guardar');
        $form->add_text('izena','izena', @$_POST['izena']);
        $form->add_text('abizena', 'abizena', @$_POST['abizena']);
        $form->add_text('goitizena', 'goitizena', @$_POST['goitizena']);
        $form->add_text('emaila', 'emaila', @$_POST['emaila']);
        $form->add_text('telefonoa', 'telefonoa', @$_POST['telefonoa']);
        $form->add_text('erabiltzailea', 'erabiltzailea', @$_POST['erabiltzailea']);
        $form->add_text('pasahitza', 'pasahitza', @$_POST['pasahitza']);
        $form->add_submit('Bazkidea gehitu');
        $form->add_errorzone($errores);
        print Template('Bazkide berria')->show($form->get_form());
    }

    public function editar($obj=array(), $errores=array()) {
        $form = new WebFormPRO('/bazkideak/bazkide/guardar');
        $form->add_hidden('id', $obj->bazkide_id);
        $form->add_text('izena','izena', $obj->izena, null);
        $form->add_text('abizena', 'abizena', $obj->abizena);
        $form->add_text('goitizena', 'goitizena', $obj->goitizena);
        $form->add_text('emaila', 'emaila', $obj->emaila);
        $form->add_text('telefonoa', 'telefonoa', $obj->telefonoa);
        $form->add_submit('Aldaketak gorde');
        $form->add_errorzone($errores);
        print Template('Bazkideta editatu')->show($form->get_form());
    }

    public function listar($coleccion=array()) {
        foreach ($coleccion as $obj) {
            unset($obj->user);
        }
        unset($coleccion->user);
        $str = new CustomCollectorViewer($coleccion, 'bazkideak', 'bazkide',
            False, True, True);
        print Template('Bazkide zerrenda')->show($str->get_table());
    }
}

?>
