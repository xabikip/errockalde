<?php

class postView {

    public function agregar($kategoriak, $errores=array()) {
        Dict::set_dict_for_webform($kategoriak, 'deitura', @$_POST['deitura']);

        $form = new WebFormPRO('/blog/post/guardar');
        $form->add_select('kategoria', 'kategoria', $kategoriak);
        $form->add_textarea('titularra','titularra', @$_POST['titularra']);
        $form->add_textarea('parrafoa', 'parrafoa', @$_POST['parrafoa']);
        $form->add_textarea('edukia', 'edukia', @$_POST['edukia'], ' class="editme"');
        $form->add_file('irudia', 'irudia', @$_POST['irudia']);
        $form->add_submit('Artikulua gehitu');
        $form->add_errorzone($errores);

        $js_europio = file_get_contents(STATIC_DIR ."js/errockalde.js");
        $html = $js_europio . $form->get_form();
        print Template('Artikulu berria')->show($html);
    }

    public function editar($obj=array(),$errores=array()) {
        $parrafoa = file_get_contents(WRITABLE_DIR . PARRAFO_DIR . "/{$obj->post_id}" );
        $edukia = file_get_contents(WRITABLE_DIR . EDUKI_DIR . "/{$obj->post_id}" );
        $obj->parrafoa = $parrafoa;
        $obj->edukia = $edukia;
        $form = new WebFormPRO('/blog/post/guardar');
        $form->add_hidden('id', $obj->post_id);
        $form->add_text('titularra','titularra', $obj->titularra);
        $form->add_textarea('parrafoa', 'parrafoa', $obj->parrafoa);
        $form->add_textarea('edukia', 'edukia', $obj->edukia);
        $form->add_submit('Aritkulua editatu');
        $form->add_errorzone($errores);
        print Template('Editar post')->show($form->get_form());
    }

    public function listar($coleccion=array()) {
        $str = CollectorViewer($coleccion, 'blog', 'post',
            false, True, True)->get_table();
        print Template('Listado de post')->show($str);
    }

    public function post($post=array()) {
        $id = $post->post_id;

        //Añado propiedades parrafoa y edukia
        $parrafoa = file_get_contents(WRITABLE_DIR . PARRAFO_DIR . "/{$id}" );
        $edukia = file_get_contents(WRITABLE_DIR . EDUKI_DIR . "/{$id}" );
        $edukia = EuropioCode::decode_preformat($edukia);
        $parrafoa = EuropioCode::decode($parrafoa);
        $post->parrafoa = $parrafoa;
        $post->edukia = $edukia;

        //Render post
        $plantilla = file_get_contents( STATIC_DIR . '/html/post.html');
        $render_post = Template($plantilla)->render($post);

        //Render aldatua
        if($post->aldatua <= 0){
            $render_post = $this->eliminar_bloque("ALDATUA", $render_post);
        }

        //Render image
        $imagen = WRITABLE_DIR . POST_IRUDI_DIR . "/{$post->post_id}";
        if (!file_exists($imagen)){
            $render_post = $this->eliminar_bloque("IRUDIA{$post->post_id}", $render_post);
        }

        print Template('Post', CUSTOM_PUBLIC_TEMPLATE)->show($render_post);
    }

    public function posts($posts=array()) {
        foreach ($posts as $post) {
            $parrafoa = file_get_contents(WRITABLE_DIR . PARRAFO_DIR . "/{$post->post_id}" );
            $edukia = file_get_contents(WRITABLE_DIR . EDUKI_DIR . "/{$post->post_id}" );
            $edukia = EuropioCode::decode_preformat($edukia);
            $parrafoa = EuropioCode::decode($parrafoa);
            $post->parrafoa = $parrafoa;
            $post->edukia = $edukia;
        }

        //Render post
        $plantilla = file_get_contents( STATIC_DIR . '/html/posts.html');
        $render_post = Template($plantilla)->render_regex('POST', $posts);

        //Render imagen
        foreach ($posts as $post) {
            $imagen = WRITABLE_DIR . POST_IRUDI_DIR . "/{$post->post_id}";
            if (!file_exists($imagen)){
                $render_post = $this->eliminar_bloque("IRUDIA{$post->post_id}", $render_post);
            }
        }

        print Template('Posts', CUSTOM_PUBLIC_TEMPLATE)->show($render_post);
    }

    # ==========================================================================
    #                       PRIVATE FUNCTIONS: Helpers
    # ==========================================================================

    private function eliminar_bloque($identificador, $plantilla) {
        $identificador = $identificador;
        $bloque_eliminar = Template($plantilla)->get_substr($identificador);
        return $render_eliminado = str_replace($bloque_eliminar, "", $plantilla);
    }

}

?>
