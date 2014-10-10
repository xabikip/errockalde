<?php

class TaldeView {

    //Mostrar form para agregar talde
    public function agregar($bazkideak, $errores=array()) {
        Dict::set_dict_for_webform($bazkideak, 'izena', @$_POST['izena']);

        //Armar un formulario
        $form = new WebFormPRO('/bazkideak/talde/guardar');
        $form->add_text('izena', 'Taldearen izena', @$_POST['izena']);
        $form->add_hidden('customurl', 'customurl', @$_POST['customurl']);
        $form->add_checkbox('bazkideak', 'Taldekideak', $bazkideak);
        $form->add_text('web', 'Web orria', @$_POST['web']);
        $form->add_text('emaila', 'Emaila', @$_POST['emaila']);
        $form->add_text('telefonoa', 'Telefonoa', @$_POST['telefonoa']);
        $form->add_textarea('deskribapena', 'Deskribapena', @$_POST['deskribapena']);
        $form->add_textarea('bandcamp', 'Bandcamp', @$_POST['bandcamp']);
        $form->add_textarea('_youtube', ' ', @$_POST['youtube'], "style='display: none;'");
        $form->add_text('youtube', 'Youtube', @$_POST['youtube']);
        $form->add_file('argazkia', 'Argazkia', @$_POST['file']);
        $form->add_submit('Taldea gehitu');
        $form->add_errorzone($errores);

        //Mostrar form para agregar talde
        $js_europio = file_get_contents(STATIC_DIR ."js/errockalde.js");
        $html = $form->get_form() . $js_europio;
        print Template('Talde berria')->show($html);
    }

    public function editar($obj=array(), $bazkideak=array(), $errores=array()) {
        Dict::set_dict_for_webform($bazkideak, 'izena', @$_POST['izena']);

        //Armar un formulario
        $form = new WebFormPRO('/bazkideak/talde/guardar');
        $form->add_hidden('id', $obj->talde_id);
        $form->add_text('izena', 'izena', $obj->izena);
        $form->add_text('customurl', 'customurl', $obj->customurl);
        $form->add_checkbox('bazkideak', 'Taldekideak', $bazkideak);
        $form->add_text('web', 'Web orria', $obj->web);
        $form->add_text('emaila', 'emaila', $obj->emaila);
        $form->add_text('telefonoa', 'telefonoa', $obj->telefonoa);
        $form->add_textarea('deskribapena', 'deskribapena', $obj->deskribapena);
        $form->add_textarea('bandcamp', 'Bandcamp', @$_POST['bandcamp']);
        $form->add_text('youtube', 'Youtube', @$_POST['youtube']);
        $form->add_file('argazkia', 'argazkia');
        $form->add_submit('Gorde');
        $form->add_errorzone($errores);

        //Mostrar form para editar talde
        $js_europio = file_get_contents(STATIC_DIR ."js/errockalde.js");
        $html = $form->get_form() . $js_europio;
        print Template('Taldea editatu')->show($html);
    }

    public function listar($coleccion=array()) {
        $this->preparar_coleccion_listar($coleccion);
        $str = new CustomCollectorViewer($coleccion, 'bazkideak', 'talde',
            False, True, True);
        print Template('Taldeen zerrenda')->show($str->get_table());
    }

    public function hasiera($taldeak=array(), $ekitaldiak=array(), $posts=array()) {
        //Modificar propiedades
        foreach($ekitaldiak as $obj) {
            $obj->ekitaldi_izena = $obj->izena;
            $obj->ordua = substr($obj->ordua, 0, 5);
        }

        //Añadimos propiedad
        foreach ($posts as $post) {
            $parrafoa = file_get_contents(WRITABLE_DIR . PARRAFO_DIR . "/{$post->post_id}" );
            $edukia = file_get_contents(WRITABLE_DIR . EDUKI_DIR . "/{$post->post_id}" );
            $edukia = EuropioCode::decode_preformat($edukia);
            $parrafoa = str_replace("&#160;", " ", EuropioCode::decode($parrafoa));
            $post->parrafoa = $parrafoa;
            $post->edukia = $edukia;
        }

        $dict = new DictCollection();
        $dict->set($ekitaldiak);
        $ekitaldi_zerrenda = $dict->collection;

        $dict = new DictCollection();
        $dict->set($posts);
        $post_zerrenda = $dict->collection;

        //Render ekitaldi, post y talde
        $plantilla = file_get_contents(STATIC_DIR . "/html/hasiera.html");
        $render_ekitaldiak = Template($plantilla)->render_regex('EKITALDIAK', $ekitaldi_zerrenda);
        $render_post = Template($render_ekitaldiak)->render_regex('POST', $post_zerrenda);
        $render_taldeak = Template($render_post)->render_regex('TALDEAK', $taldeak);

        //Render imagen
        foreach ($posts as $post) {
            $imagen = WRITABLE_DIR . POST_IRUDI_DIR . "/{$post->post_id}";
            if (!file_exists($imagen)){
                $render_taldeak = $this->eliminar_bloque("IRUDIA{$post->post_id}", $render_taldeak);
            }
        }

        print Template('RockHeltzia', CUSTOM_PUBLIC_TEMPLATE)->show($render_taldeak);
    }


    public function taldeak($taldeak=array()) {
        //Render taldeak
        $plantilla = file_get_contents(STATIC_DIR . '/html/taldeak.html');
        $render_taldeak = Template($plantilla)->render_regex('TALDEAK', $taldeak);

        //Render imagen
        foreach ($taldeak as $talde) {
            $imagen = WRITABLE_DIR . IRUDI_DIR . "/{$talde->talde_id}";
            if (!file_exists($imagen)){
                $render_taldeak = $this->eliminar_bloque("IRUDIA{$talde->talde_id}", $render_taldeak);
            }
        }

        //Mostrar
        print Template('Taldeak', CUSTOM_PUBLIC_TEMPLATE)->show($render_taldeak);
    }


    public function taldea($taldea=array()) {
        $id = $taldea->talde_id;

        //Añado propiedad
        foreach ($taldea->bazkide_collection as $obj) {
            $obj->bazkide_izena = $obj->izena;
        }

        //Render grupo
        $plantilla = file_get_contents( STATIC_DIR . '/html/taldea.html');
        $render_taldea = Template($plantilla)->render($taldea);

        //Render imagen
        $imagen = WRITABLE_DIR . IRUDI_DIR . "/{$id}";
        if (!file_exists($imagen)) {
            $render_taldea = $this->eliminar_bloque("IRUDIA{$id}", $render_taldea);
        }

        //Mostrar
        $bandcamp = WRITABLE_DIR . BANDCAMP_DIR . "/{$id}.ini";
        $youtube  = WRITABLE_DIR . YOUTUBE_DIR . "/{$id}.ini";
        $render_bandcamp = $this->render_exist($bandcamp, $render_taldea, $id, "BANDCAMP");
        $render_youtube  = $this->render_exist($youtube, $render_bandcamp, $id, "YOUTUBE");
        $render_bazkidea = Template($render_youtube)->render_regex('BAZKIDEAK',
            $taldea->bazkide_collection);
        print Template('Taldeak', CUSTOM_PUBLIC_TEMPLATE)->show($render_bazkidea);
    }

    # ==========================================================================
    #                       PRIVATE FUNCTIONS: Helpers
    # ==========================================================================

    private function preparar_coleccion_listar(&$coleccion) {
        foreach ($coleccion as $obj) {
            $obj->partaideak = array();
            $obj->diskoak = array();
            foreach ($obj->bazkide_collection as $bazkide) {
                $obj->partaideak[] = $bazkide->izena;
            }
            foreach ($obj->diskoa_collection as $diskoa) {
                $obj->diskoak[] = $diskoa->izena;
            }
            unset($obj->bazkide_collection);
            unset($obj->diskoa_collection);
            $obj->partaideak = nl2br(implode("\n", $obj->partaideak));
            $obj->diskoak = nl2br(implode("\n", $obj->diskoak));
        }
    }

    private function render_exist($file, $html, $id, $identificador){
        if (file_exists($file)) {
            $render = $this->render_ini($file, $html);
        } else {
            $render = $this->eliminar_bloque($identificador, $html);
        }
        return $render;
    }

    private function eliminar_bloque($identificador, $plantilla) {
        $identificador = $identificador;
        $bloque_eliminar = Template($plantilla)->get_substr($identificador);
        return $render_eliminado = str_replace($bloque_eliminar, "", $plantilla);
    }

    private function render_ini($ini, $plantilla){
         $ini_parse = parse_ini_file($ini, False);
         return $render_ini = Template($plantilla)->render($ini_parse);
    }

}

?>
