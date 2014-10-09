<?php

class DiskoaView { 

    public function agregar() {
        $form = new WebForm('/bazkideak/diskoa/guardar');
        # ...
        $form->add_submit('Agregar');
        print Template('Agregar Diskoa')->show($form->show());
    }
    
    public function editar($obj=array()) {
        $form = new WebForm('/bazkideak/diskoa/guardar');
        $form->add_hidden('id', $obj->diskoa_id);
        # ...
        $form->add_submit('Agregar');
        print Template('Editar Diskoa')->show($form->show());
    }

    public function listar($coleccion=array()) {
        $str = CollectorViewer($coleccion, 'bazkideak', 'diskoa',
            True, True, True)->get_table();
        print Template('Listado de Diskoa')->show($str);
    }
}

?>
