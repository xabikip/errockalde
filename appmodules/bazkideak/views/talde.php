<?php

class TaldeView {

    public function agregar($bazkideak, $errores = array()) {
        Dict::set_dict_for_webform($bazkideak, 'izena', @$_POST['izena']);

        $form = new WebFormPRO('/bazkideak/talde/guardar');
        $form->add_text('izena', 'izena', @$_POST['izena']);
        $form->add_checkbox('bazkideak', 'Taldekideak', $bazkideak);
        $form->add_text('web', 'Web orria', @$_POST['web']);
        $form->add_text('emaila', 'emaila', @$_POST['emaila']);
        $form->add_text('telefonoa', 'telefonoa', @$_POST['telefonoa']);
        $form->add_submit('Taldea gehitu');
        $form->add_errorzone($errores);
        print Template('Talde berria')->show($form->get_form());
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
        foreach ($coleccion as &$obj) {
            $obj->partaideak = array();
            foreach ($obj->bazkide_collection as $bazkide) {
                $obj->partaideak[] = $bazkide->izena;
            }
            unset($obj->bazkide_collection);
            $obj->partaideak = nl2br(implode("\n", $obj->partaideak));
        }
        $str = new CustomCollectorViewer($coleccion, 'bazkideak', 'talde',
            false, True, True);
        print Template('Taldeen zerrenda')->show($str->get_table());
    }
}

?>
