<?php

class BazkideView {

    public function agregar() {
        $form = new WebForm('/bazkideak/bazkide/guardar');
        $form->add_text('izena');
        $form->add_text('abizena');
        $form->add_text('goitizena');
        $form->add_text('emaila');
        $form->add_text('telefonoa');
        $form->add_submit('Bazkidea gehitu');
        print Template('Bazkide berria')->show($form->show());
    }

    public function editar($obj=array()) {
        $form = new WebForm('/bazkideak/bazkide/guardar');
        $form->add_hidden('id', $obj->MODELO_id);
        # ...
        $form->add_submit('Agregar');
        print Template('Editar Bazkide')->show($form->show());
    }

    public function listar($coleccion=array()) {
        $str = CollectorViewer($coleccion, 'bazkideak', 'bazkide',
            True, True, True)->get_table();
        print Template('Listado de Bazkide')->show($str);
    }
}

?>
