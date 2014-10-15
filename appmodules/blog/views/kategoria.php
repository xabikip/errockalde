<?php

class kategoriaView {

    public function agregar($errores=array()) {
        $form = new WebFormPRO('/blog/kategoria/guardar');
        $form->add_text('deitura','deitura', @$_POST['deitura']);
        $form->add_submit('Kategoria gehitu');
        $form->add_errorzone($errores);
        print Template('Kategoria gehitu')->show($form->get_form());
    }

    public function editar($obj=array(), $errores=array()) {
        $form = new WebFormPRO('/blog/kategoria/guardar');
        $form->add_hidden('id', $obj->kategoria_id);
        $form->add_text('deitura','deitura', $obj->deitura);
        $form->add_submit('Aldatu');
        $form->add_errorzone($errores);
        print Template('Editar kategoria')->show($form->get_form());
    }

    public function listar($coleccion=array()) {
        $str = new CustomCollectorViewer($coleccion, 'blog', 'kategoria',
            False, True, True);
        print Template('Listado de kategoria')->show($str->get_table());
    }
}

?>
