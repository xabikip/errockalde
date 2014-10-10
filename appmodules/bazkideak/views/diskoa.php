<?php

class DiskoaView {

    public function agregar($taldeak, $errores=array()) {
        Dict::set_dict_for_webform($taldeak, 'izena', @$_POST['izena']);

        $form = new WebFormPRO('/bazkideak/diskoa/guardar');
        $form->add_select('taldea', 'Taldea', $taldeak);
        $form->add_text('izena', 'Diskoaren izena', @$_POST['izena']);
        $form->add_text('data', 'Data', @$_POST['data']);
        $form->add_text('iraupena', 'Iraupena', @$_POST['iraupena']);
        $form->add_textarea('abestiak', 'Abestiak', @$_POST['abestiak']);
        $form->add_file('azala', 'Azala', @$_POST['azala']);
        $form->add_submit('Diskoa gehitu');
        $form->add_errorzone($errores);

        print Template('Diskoa gehitu')->show($form->get_form());
    }

    public function editar($obj=array()) {
        $form = new WebForm('/bazkideak/diskoa/guardar');
        $form->add_hidden('id', $obj->diskoa_id);
        # ...
        $form->add_submit('Agregar');
        print Template('Editar Diskoa')->show($form->show());
    }

    public function listar($coleccion=array()) {
        $str = CollectorViewer($coleccion, 'bazkideak', 'diskoa',
            True, True, True)->get_table();
        print Template('Listado de Diskoa')->show($str);
    }
}

?>
