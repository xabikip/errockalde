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

    public function editar($obj=array(), $taldeak, $errores=array()) {
        Dict::set_dict_for_webform($taldeak, 'izena', $obj->talde);

        $form = new WebFormPro('/bazkideak/diskoa/guardar');
        $form->add_hidden('id', $obj->diskoa_id);
        $form->add_select('taldea', 'Taldea', $taldeak);
        $form->add_text('izena', 'Diskoaren izena', $obj->izena);
        $form->add_text('data', 'Data', $obj->data);
        $form->add_text('iraupena', 'Iraupena', $obj->iraupena);
        $form->add_textarea('abestiak', 'Abestiak', $obj->abestiak);
        $form->add_submit('Aldatu');

        print Template('Editar Diskoa')->show($form->get_form());
    }

    public function listar($coleccion=array()) {
        $this->preparar_coleccion_listar($coleccion);
        $str = new CustomCollectorViewer($coleccion, 'bazkideak', 'diskoa',
            False, True, True);
        print Template('Disko zerrenda')->show($str->get_table());
    }

     # ==========================================================================
    #                       PRIVATE FUNCTIONS: Helpers
    # ==========================================================================

    private function preparar_coleccion_listar(&$coleccion) {
        foreach ($coleccion as $obj) {
            $id = $obj->talde;
            $taldea = DataHandler('talde')->filter("talde_id = $id");
            $obj->talde = $taldea[0]['izena'];
        }
    }

}

?>
