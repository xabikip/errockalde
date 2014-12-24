<?php

class postView {

    public function agregar($kategoriak, $errores=array()) {
        Dict::set_dict_for_webform($kategoriak, 'deitura', @$_POST['deitura']);

        $form = new WebFormPRO('/blog/post/guardar');
        $form->add_select('kategoria', 'kategoria', $kategoriak);
        $form->add_textarea('titularra','titularra', @$_POST['titularra']);
        $form->add_textarea('parrafoa', 'parrafoa', @$_POST['parrafoa']);
        $form->add_textarea('edukia', 'edukia', @$_POST['edukia'], 'class="editme"');
        $form->add_file('irudia', 'irudia', @$_POST['irudia']);
        $form->add_submit('Artikulua gehitu');
        $form->add_errorzone($errores, "Kontuz!");

        $js_europio = file_get_contents(CUSTOM_STATIC_DIR ."/js/errockalde.js");
        $html = $js_europio . $form->get_form();
        render_final_back($html, "Artikulu berria");
    }

    public function editar($obj=array(),$errores=array(), $kategoriak) {
        Dict::set_dict_for_webform($kategoriak, 'deitura', $obj->kategoria->kategoria_id);

        $parrafoa = file_get_contents(WRITABLE_DIR . PARRAFO_DIR . "/{$obj->post_id}" );
        $edukia = file_get_contents(WRITABLE_DIR . EDUKI_DIR . "/{$obj->post_id}" );
        $obj->parrafoa = EuropioCode::decode($parrafoa);;
        $obj->edukia = EuropioCode::decode($edukia);;

        $form = new WebFormPRO('/blog/post/guardar');
        $form->add_select('kategoria', 'kategoria', $kategoriak);
        $form->add_textarea('titularra','titularra', $obj->titularra);
        $form->add_textarea('parrafoa', 'parrafoa', $obj->parrafoa);
        $form->add_textarea('edukia', 'edukia', $obj->edukia, 'class="editme"');

        $form->add_file('irudia', 'irudia');
        $html_irudia = file_get_contents( CUSTOM_STATIC_DIR . '/html/back/blog/edit_post_irudia.html');
        $render = Template($html_irudia)->render($obj);
        $form->fields[] = $render;

        $form->add_hidden('id', $obj->post_id);
        $form->add_hidden('sortua', $obj->sortua);
        $form->add_submit('Aritkulua editatu');
        $form->add_errorzone($errores, "Kontuz!");

        $js_europio = file_get_contents(CUSTOM_STATIC_DIR ."/js/errockalde.js");
        $html = $js_europio . $form->get_form();
        render_final_back($html, "Artikulua aldatu");
    }

    public function listar($coleccion=array()) {
        foreach ($coleccion as $obj) {
            $obj->kategoria = $obj->kategoria->deitura;
            $obj->user = $obj->user->name;
            unset($obj->slug);
        }

        $str = new CustomCollectorViewer($coleccion, 'blog', 'post',
            false, True, True);
        render_final_back($str->get_table(), "Artikulu zerrenda");
    }

    public function post($post=array(), $kategoriak) {

        $post->kategoria = $post->kategoria->deitura;
        $post->user = $post->user->name;

        //Añado propiedades parrafoa y edukia
        $id = $post->post_id;
        $parrafoa = file_get_contents(WRITABLE_DIR . PARRAFO_DIR . "/{$id}" );
        $edukia = file_get_contents(WRITABLE_DIR . EDUKI_DIR . "/{$id}" );
        $edukia = EuropioCode::decode_preformat($edukia);
        $parrafoa = str_replace("&#160;", " ", EuropioCode::decode($parrafoa));
        $post->parrafoa = $parrafoa;
        $post->edukia = $edukia;

        //Render post
        $plantilla = file_get_contents( CUSTOM_STATIC_DIR . '/html/front/blog/post.html');
        $render_post = Template($plantilla)->render($post);

        //Render kategoriak
        $render_post = Template($render_post)->render_regex('KATEGORIAK', $kategoriak);

        //Render data
        if($post->aldatua == "0000-00-00"){
            $render_post = $this->eliminar_bloque("ALDATUA", $render_post);
        }else{
            $render_post = $this->eliminar_bloque("SORTUA", $render_post);
        }

        //Render image
        $imagen = WRITABLE_DIR . POST_IRUDI_DIR . "/{$post->post_id}";
        if (!file_exists($imagen)){
            $render_post = $this->eliminar_bloque("IRUDIA{$post->post_id}", $render_post);
        }

        print Template('Post', CUSTOM_PUBLIC_TEMPLATE)->show($render_post);
    }

    public function posts($posts=array(), $kategoriak) {

        foreach ($posts as &$post) {
            (isset($post->post_id)) ? $id = $post->post_id : $id = $post['post_id'];
            $parrafoa = file_get_contents(WRITABLE_DIR . PARRAFO_DIR . "/{$id}" );
            $edukia = file_get_contents(WRITABLE_DIR . EDUKI_DIR . "/{$id}" );
            $edukia = EuropioCode::decode_preformat($edukia);
            $parrafoa = str_replace("&#160;", " ", EuropioCode::decode($parrafoa));
            if (isset($post->post_id)){
                $post->parrafoa = substr($edukia, 0, 120) . "...";
                $post->edukia = $edukia;
                $post->user = $post->user->name;
            }else{
                $post['parrafoa'] = substr($edukia, 0, 120) . "...";
                $post['edukia'] = $edukia;
                $post['user'] = $post->user->name;
            };

        }

        //Render post
        $plantilla = file_get_contents( CUSTOM_STATIC_DIR . '/html/front/blog/posts.html');
        $render_post = Template($plantilla)->render_regex('POST', $posts);

        //Render kategoriak
        $render_post = Template($render_post)->render_regex('KATEGORIAK', $kategoriak);

        //Render imagen y fecha
        foreach ($posts as &$post) {
            (isset($post->post_id)) ? $id = $post->post_id : $id = $post['post_id'];

            $imagen = WRITABLE_DIR . POST_IRUDI_DIR . "/{$id}";
            if (!file_exists($imagen)){
                $render_post = $this->eliminar_bloque("IRUDIA{$id}", $render_post);
            }else{
                $render_post = $this->eliminar_bloque("MUSIKAGUNE{$id}", $render_post);
            }

            if($post->aldatua == "0000-00-00"){
                $render_post = $this->eliminar_bloque("ALDATUA{$id}", $render_post);
            }else{
                $render_post = $this->eliminar_bloque("SORTUA{$id}", $render_post);
            }
        }

        print Template('Posts', CUSTOM_PUBLIC_TEMPLATE)->show($render_post);
    }

    # ==========================================================================
    #                       PRIVATE FUNCTIONS: Helpers
    # ==========================================================================

    private function eliminar_bloque($identificador, $plantilla) {
        $identificador = $identificador;
        $bloque_eliminar = Template($plantilla)->get_substr($identificador, FALSE);
        return str_replace($bloque_eliminar, "", $plantilla);
    }

}

?>
