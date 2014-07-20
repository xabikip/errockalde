<?php

class kategoriaView { 

    public function agregar() {
        $form = new WebForm('/blog/kategoria/guardar');
        # ...
        $form->add_submit('Agregar');
        print Template('Agregar kategoria')->show($form->show());
    }
    
    public function editar($obj=array()) {
        $form = new WebForm('/blog/kategoria/guardar');
        $form->add_hidden('id', $obj->kategoria_id);
        # ...
        $form->add_submit('Agregar');
        print Template('Editar kategoria')->show($form->show());
    }

    public function listar($coleccion=array()) {
        $str = CollectorViewer($coleccion, 'blog', 'kategoria',
            True, True, True)->get_table();
        print Template('Listado de kategoria')->show($str);
    }
}

?>
