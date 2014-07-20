<?php

class postView {

    public function agregar() {
        $form = new WebFormPRO('/blog/post/guardar');
        $form->add_text('izena','izena', @$_POST['izena']);
        $form->add_text('abizena', 'abizena', @$_POST['abizena']);
        $form->add_text('goitizena', 'goitizena', @$_POST['goitizena']);
        $form->add_text('emaila', 'emaila', @$_POST['emaila']);
        $form->add_text('telefonoa', 'telefonoa', @$_POST['telefonoa']);
        $form->add_text('erabiltzailea', 'erabiltzailea', @$_POST['erabiltzailea']);
        $form->add_text('pasahitza', 'pasahitza', @$_POST['pasahitza']);
        $form->add_submit('Bazkidea gehitu');
        $form->add_errorzone($errores);
        print Template('Agregar post')->show($form->show());
    }

    public function editar($obj=array()) {
        $form = new WebForm('/blog/post/guardar');
        $form->add_hidden('id', $obj->post_id);
        # ...
        $form->add_submit('Agregar');
        print Template('Editar post')->show($form->show());
    }

    public function listar($coleccion=array()) {
        $str = CollectorViewer($coleccion, 'blog', 'post',
            True, True, True)->get_table();
        print Template('Listado de post')->show($str);
    }
}

?>
