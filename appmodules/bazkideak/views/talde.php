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
        $form->add_textarea('_youtube', ' ', @$_POST['_youtube'], "style='display: none;'");
        $form->add_text('youtube', 'Youtube', @$_POST['youtube']);
        $form->add_file('argazkia', 'Argazkia', @$_POST['file']);
        $form->add_submit('Taldea gehitu');
        $form->add_errorzone($errores);

        //Mostrar form para agregar talde
        $js_europio = file_get_contents(CUSTOM_STATIC_DIR ."/js/errockalde.js");
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
        $form->add_text('youtube', 'Youtube', @$_POST['youtube']);

        $form->add_file('argazkia', 'argazkia');
        $html_irudia = file_get_contents( CUSTOM_STATIC_DIR . '/html/back/bazkideak/edit_talde_irudia.html');
        $render = Template($html_irudia)->render($obj);
        $form->fields[] = $render;

        $form->add_submit('Gorde');
        $form->add_errorzone($errores);

        //Mostrar form para editar talde
        $js_europio = file_get_contents(CUSTOM_STATIC_DIR ."/js/errockalde.js");
        $html = $form->get_form() . $js_europio;
        print Template('Taldea editatu')->show($html);
    }

    public function listar($coleccion=array()) {
        $this->preparar_coleccion_listar($coleccion);
        $str = new CustomCollectorViewer($coleccion, 'bazkideak', 'talde',
            False, True, True);
        print Template('Taldeen zerrenda')->show($str->get_table());
    }

    public function kontaktua() {

        $plantilla = file_get_contents(CUSTOM_STATIC_DIR . "/html/front/kontaktua.html");
        print Template('kontaktua', CUSTOM_PUBLIC_TEMPLATE)->show($plantilla);
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
            $post->parrafoa = substr($edukia, 0, 450);
            $post->edukia = $edukia;
        }

        $dict = new DictCollection();
        $dict->set($ekitaldiak);
        $ekitaldi_zerrenda = $dict->collection;

        $dict = new DictCollection();
        $dict->set($posts);
        $post_zerrenda = $dict->collection;

        //Render ekitaldi, post,slide y talde
        $plantilla = file_get_contents(CUSTOM_STATIC_DIR . "/html/front/hasiera.html");
        $render_ekitaldiak = Template($plantilla)->render_regex('EKITALDIAK', $ekitaldi_zerrenda);
        $render_slide = Template($render_ekitaldiak)->render_regex('SLIDEPOST', $post_zerrenda);
        $render_post = Template($render_slide)->render_regex('POST', $post_zerrenda);


        //Render imagen y creado/modificado
        foreach ($posts as $post) {
            $imagen = WRITABLE_DIR . POST_IRUDI_DIR . "/{$post->post_id}";
            if (!file_exists($imagen)){
                $render_post = $this->eliminar_bloque("IRUDIA{$post->post_id}", $render_post);
            }else{
                $render_post = $this->eliminar_bloque("MUSIKAGUNE{$post->post_id}", $render_post);
            }

            if($post->aldatua <= 0){
                $render_post = $this->eliminar_bloque("ALDATUA{$post->post_id}", $render_post);
            }else{
                $render_post = $this->eliminar_bloque("SORTUA{$post->post_id}", $render_post);
            }
        }

        //Render kartela
        foreach ($ekitaldiak as $ekitaldi) {
            $imagen = WRITABLE_DIR . EKITALDI_IRUDI_DIR . "/{$ekitaldi->ekitaldia_id}";
            if (!file_exists($imagen)){
                $render_post = $this->eliminar_bloque("KARTELA{$ekitaldi->ekitaldia_id}", $render_post);
            }
        }

        print Template('RockHeltzia', CUSTOM_PUBLIC_TEMPLATE)->show($render_post);
    }


    public function taldeak($taldeak=array()) {
        //Render taldeak
        $plantilla = file_get_contents(CUSTOM_STATIC_DIR . '/html/front/bazkideak/taldeak.html');
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
        $plantilla = file_get_contents( CUSTOM_STATIC_DIR . '/html/front/bazkideak/taldea.html');
        $render_taldea = Template($plantilla)->render($taldea);

        //Render imagen
        $imagen = WRITABLE_DIR . IRUDI_DIR . "/{$id}";
        if (!file_exists($imagen)) {
            $render_taldea = $this->eliminar_bloque("IRUDIA{$id}", $render_taldea);
        }

        //Render discos
        if (empty($taldea->diskoa_collection)){
            $render_diskoa = $this->eliminar_bloque("LANAK", $render_taldea);
        } else{
            $disko_ezabatu = array();
            $bandcamp_ezabatu = array();
            foreach ($taldea->diskoa_collection as $obj) {
                $diskoa_id = $obj->diskoa_id;
                $bandcamp = WRITABLE_DIR . BANDCAMP_DIR . "/{$diskoa_id}.ini";
                if (file_exists($bandcamp)) {
                    $ini_parse = parse_ini_file($bandcamp, False);
                    $obj->album_id = $ini_parse['album_id'];
                    $obj->talde_slug = $ini_parse['talde_slug'];
                    $obj->album_slug = $ini_parse['album_slug'];
                    $obj->album = $ini_parse['album'];
                    $obj->grupo = $ini_parse['grupo'];
                    $obj->disko_izena = $obj->izena;
                    $disko_ezabatu[] = $diskoa_id;
                } else{
                    $obj->disko_izena = $obj->izena;
                    $bandcamp_ezabatu[] = $diskoa_id;
                }
            }
            $render_diskoa = Template($render_taldea)->render_regex('LANA', $taldea->diskoa_collection);

            foreach ($disko_ezabatu as $diskoa_id) {
                $render_diskoa = $this->eliminar_bloque("DISKOA{$diskoa_id}", $render_diskoa);
            }

            foreach ($bandcamp_ezabatu as $diskoa_id) {
                $render_diskoa = $this->eliminar_bloque("BANDCAMP{$diskoa_id}", $render_diskoa);
            }
        }

        //Render youtube
        $youtube  = WRITABLE_DIR . YOUTUBE_DIR . "/{$id}.ini";
        $render_youtube  = $this->render_exist($youtube, $render_diskoa, $id, "YOUTUBE");

        //Render bazkide
        $render_bazkidea = Template($render_youtube)->render_regex('BAZKIDEAK',
            $taldea->bazkide_collection);

        //Mostrar
        print Template('Taldeak', CUSTOM_PUBLIC_TEMPLATE)->show($render_bazkidea);
    }

    public function añadir_disco($taldea=array()) {
        $plantilla = file_get_contents( CUSTOM_STATIC_DIR . '/html/back/bazkideak/diskoa_gehitu_mezua.html');
        $render = Template($plantilla)->render($taldea);
        print Template('Taldeen zerrenda')->show($render);
    }

    # ==========================================================================
    #                       PRIVATE FUNCTIONS: Helpers
    # ==========================================================================

    private function preparar_coleccion_listar(&$coleccion) {
        foreach ($coleccion as $obj) {
            unset($obj->bazkide_collection);
            unset($obj->diskoa_collection);
            unset($obj->web);
            unset($obj->deskribapena);
            unset($obj->customurl);
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
