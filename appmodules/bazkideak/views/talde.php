<?php

class TaldeView {

    public function agregar() {
        $form = new WebForm('/bazkideak/talde/guardar');
        $form->add_text('izena');
        $form->add_text('web', 'Web orria');
        $form->add_text('emaila');
        $form->add_text('telefonoa');
        $form->add_submit('Taldea gehitu');
        print Template('Talde berria')->show($form->show());
    }

    public function editar($obj=array()) {
        $form = new WebForm('/bazkideak/talde/guardar');
        $form->add_hidden('id', $obj->MODELO_id);
        # ...
        $form->add_submit('Agregar');
        print Template('Editar Talde')->show($form->show());
    }

    public function listar($coleccion=array()) {
        $str = CollectorViewer($coleccion, 'bazkideak', 'talde',
            True, True, True)->get_table();
        print Template('Listado de Talde')->show($str);
    }
}

?>
