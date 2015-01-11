<?php

class DiskoaView {

    public function agregar($taldeak, $errores=array()) {
        Dict::set_dict_for_webform($taldeak, 'izena', @$_POST['taldea_id']);

        $form = new WebFormPRO('/bazkideak/diskoa/guardar');
        $form->add_select('taldea', 'Taldea', $taldeak);
        $form->add_text('izena', 'Diskoaren izena', @$_POST['izena']);
        $form->add_text('data', 'Data', @$_POST['data']);
        $form->add_text('iraupena', 'Iraupena', @$_POST['iraupena']);
        $form->add_textarea('abestiak', 'Abestiak', @$_POST['abestiak'], 'class="editme"');
        $form->add_textarea('bandcamp', 'Bandcamp', @$_POST['bandcamp']);
        $form->add_file('azala', 'Azala', @$_POST['azala']);
        $form->add_submit('Diskoa gehitu');
        $form->add_errorzone($errores, "Kontuz!");

        //Mostrar form para agregar talde
        $js_europio = file_get_contents(CUSTOM_STATIC_DIR ."/js/astinddu.js");
        $html = $form->get_form() . $js_europio;
        render_final_back($html, "Diskoa gehitu");

    }

    public function editar($obj=array(), $taldeak, $errores=array()) {
        Dict::set_dict_for_webform($taldeak, 'izena', $obj->talde);

        $form = new WebFormPro('/bazkideak/diskoa/guardar');
        $form->add_select('taldea', 'Taldea', $taldeak);
        $form->add_text('izena', 'Diskoaren izena', $obj->izena);
        $form->add_text('data', 'Data', $obj->data);
        $form->add_text('iraupena', 'Iraupena', $obj->iraupena);
        $form->add_textarea('abestiak', 'Abestiak', $obj->abestiak,'class="editme"');
        $form->add_textarea('bandcamp', 'Bandcamp', @$_POST['bandcamp']);

        $form->add_file('azala', 'azala');
        $html_irudia = file_get_contents( CUSTOM_STATIC_DIR . '/html/back/bazkideak/edit_azala_irudia.html');
        $render = Template($html_irudia)->render($obj);
        $form->fields[] = $render;

        $form->add_hidden('id', $obj->diskoa_id);
        $form->add_submit('Aldatu');
        $form->add_errorzone($errores, "Kontuz!");

        //Mostrar form para agregar talde
        $js_europio = file_get_contents(CUSTOM_STATIC_DIR ."/js/astinddu.js");
        $html = $form->get_form() . $js_europio;
        render_final_back($html, "Diskoa editatu");
    }

    public function listar($coleccion=array()) {
        $this->preparar_coleccion_listar($coleccion);
        $str = new CustomCollectorViewer($coleccion, 'bazkideak', 'diskoa',
            False, True, True);
        render_final_back($str->get_table(), "Disko zerrenda");
    }

    public function preguntar($obj=array()) {
        $plantilla = file_get_contents( CUSTOM_STATIC_DIR . '/html/back/bazkideak/diskoa_gehitu_berriz.html');
        $render = Template($plantilla)->render($obj);
        render_final_back($render, "Diskoak");
    }

    # ==========================================================================
    #                       PRIVATE FUNCTIONS: Helpers
    # ==========================================================================

    private function preparar_coleccion_listar(&$coleccion) {
        foreach ($coleccion as $obj) {
            $id = $obj->talde;
            $taldea = DataHandler('talde')->filter("talde_id = $id");
            $obj->talde = $taldea[0]['izena'];
            unset($obj->abestiak);
        }
    }

}

?>
