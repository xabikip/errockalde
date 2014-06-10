<?php

class TaldeView {

    public function agregar($bazkideak, $errores = array()) {
        Dict::set_dict_for_webform($bazkideak, 'izena', @$_POST['izena']);

        $form = new WebFormPRO('/bazkideak/talde/guardar');
        $form->add_instructions('customurl datua zure taldearen helbidearen
            bukaeran zer ageri nahi den izango da. Ezingo da hutsunerik jarri,
            Adib: http://www.errocka.lde.net/taldeak/nire-taldea');
        $form->add_text('izena', 'Taldearen izena', @$_POST['izena']);
        $form->add_hidden('customurl', 'customurl', @$_POST['izena']);
        $form->add_checkbox('bazkideak', 'Taldekideak', $bazkideak);
        $form->add_text('web', 'Web orria', @$_POST['web']);
        $form->add_text('emaila', 'Emaila', @$_POST['emaila']);
        $form->add_text('telefonoa', 'Telefonoa', @$_POST['telefonoa']);
        $form->add_textarea('deskribapena', 'Deskribapena', @$_POST['deskribapena']);
        $form->add_file('argazkia', 'Argazkia', @$_POST['file']);
        $form->add_submit('Taldea gehitu');
        $form->add_errorzone($errores);
        print Template('Talde berria')->show($form->get_form());
    }

    public function editar($obj=array(), $bazkideak=array(), $errores=array()) {
        Dict::set_dict_for_webform($bazkideak, 'izena', @$_POST['izena']);

        $form = new WebFormPRO('/bazkideak/talde/guardar');
        $form->add_hidden('id', $obj->talde_id);
        $form->add_text('izena', 'izena', $obj->izena);
        $form->add_text('customurl', 'customurl', $obj->customurl);
        $form->add_checkbox('bazkideak', 'Taldekideak', $bazkideak);
        $form->add_text('web', 'Web orria', $obj->web);
        $form->add_text('emaila', 'emaila', $obj->emaila);
        $form->add_text('telefonoa', 'telefonoa', $obj->telefonoa);
        $form->add_textarea('deskribapena', 'deskribapena', $obj->deskribapena);
        $form->add_file('argazkia', 'argazkia', @$_POST['file']);
        $form->add_submit('Gorde');
        $form->add_errorzone($errores);
        print Template('Taldea editatu')->show($form->get_form());
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

    public function hasiera($taldeak=array(), $ekitaldiak=array()) {

        foreach ($ekitaldiak as $obj) {
            $obj->ekitaldi_izena = $obj->izena;
            $obj->ordua = substr($obj->ordua, 0,5);
        }

        $dict = new DictCollection();
        $dict->set($ekitaldiak);
        $ekitaldi_zerrenda = $dict->collection;

        $plantilla_taldeak = file_get_contents( STATIC_DIR . '/html/taldeak_hasiera.html');
        $plantilla_ekitaldiak = file_get_contents( STATIC_DIR . '/html/agenda_hasiera.html');
        $plantilla_albisteak = file_get_contents( STATIC_DIR . '/html/albisteak_hasiera.html');

        $render_taldeak = Template($plantilla_taldeak)->render_regex('TALDEAK', $taldeak);
        $render_ekitaldiak = Template($plantilla_ekitaldiak)->render_regex('EKITALDIAK', $ekitaldi_zerrenda);
        $render_albisteak = Template($plantilla_albisteak)->render();

        $render_final = $render_ekitaldiak . $render_albisteak . $render_taldeak;

        print Template('RockHeltzia', CUSTOM_PUBLIC_TEMPLATE)->show($render_final);

    }

    public function taldeak($taldeak=array()) {

        $plantilla = file_get_contents( STATIC_DIR . '/html/taldeak.html');

        $render_taldeak = Template($plantilla)->render_regex('TALDEAK', $taldeak);

        foreach ($taldeak as $talde) {
            $ruta = WRITABLE_DIR . "/bazkideak/taldea/irudiak/{$talde->talde_id}";
            if (!file_exists($ruta)){
                $identificador = "IRUDIA{$talde->talde_id}";
                $bloque_eliminar = Template($render_taldeak)->get_substr($identificador);
                $render_taldeak = str_replace($bloque_eliminar, "", $render_taldeak);
            }
        }

        print Template('Taldeak', CUSTOM_PUBLIC_TEMPLATE)->show($render_taldeak);

    }

    public function taldea($taldea=array()) {

        foreach ($taldea->bazkide_collection as $obj) {
            $obj->bazkide_izena = $obj->izena;
        }

        $plantilla = file_get_contents( STATIC_DIR . '/html/taldea.html');

        $render_taldea = Template($plantilla)->render($taldea);

        $ruta = WRITABLE_DIR . "/bazkideak/taldea/irudiak/{$taldea->talde_id}";

        if (!file_exists($ruta)){
            $identificador = "IRUDIA{$taldea->talde_id}";
            $bloque_eliminar = Template($render_taldea)->get_substr($identificador);
            $render_taldea = str_replace($bloque_eliminar, "", $render_taldea);
        }

        $render_bazkidea = Template($render_taldea)->render_regex('BAZKIDEAK', $taldea->bazkide_collection);

        print Template('Taldeak', CUSTOM_PUBLIC_TEMPLATE)->show($render_bazkidea);

    }
}

?>
