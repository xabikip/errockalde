<?php

class kategoriaView {

    public function agregar($errores=array()) {
        $form = new WebFormPRO('/blog/kategoria/guardar');
        if($errores) $form->fields[] = añadir_errores($errores);
        $form->add_text('deitura','deitura', @$_POST['deitura']);
        $form->add_submit('Kategoria gehitu');
        $form->add_errorzone($errores);
        render_final_back($form->get_form(), "Kategoria gehitu");
    }

    public function editar($obj=array(), $errores=array()) {
        $form = new WebFormPRO('/blog/kategoria/guardar');
        if($errores) $form->fields[] = añadir_errores($errores);
        $form->add_hidden('id', $obj->kategoria_id);
        $form->add_text('deitura','deitura', $obj->deitura);
        $form->add_submit('Aldatu');
        $form->add_errorzone($errores);
        render_final_back($form->get_form(), "Kategoria aldatu");
    }

    public function listar($coleccion=array()) {
        $str = new CustomCollectorViewer($coleccion, 'blog', 'kategoria',
            False, True, True);
        render_final_back($str->get_table(), "Kategoria zerrenda");
    }
}

?>
