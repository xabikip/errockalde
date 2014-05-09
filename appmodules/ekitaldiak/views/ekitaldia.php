<?php

class EkitaldiaView {

    public function agregar($ekitaldimotak, $errores = array()) {
        Dict::set_dict_for_webform($ekitaldimotak, 'deitura', @$_POST['deitura']);

        $form = new WebForm('/ekitaldiak/ekitaldia/guardar');
        $form->add_text('ekitaldi_izena', 'Ekitaldiaren Izena',@$_POST['ekitaldi_izena']);
        $form->add_select('ekitaldimota', $ekitaldimotak, 'Ekitaldi Mota');
        $form->add_text('data', 'data', @$_POST['data']);
        $form->add_text('ordua', 'ordua', @$_POST['ordua']);
        $form->add_text('izena', 'Lekuaren Izena', @$_POST['izena']);
        $form->add_text('helbidea', 'helbidea', @$_POST['helbidea']);
        $form->add_text('herria', 'herria', @$_POST['helbidea']);
        $form->add_submit('Ekitaldia gehitu');
        $form->add_error_zone($errores);
        print Template('Ekitaldi berria')->show($form->show());
    }

    public function editar($ekitaldimotak, $obj=array()) {
        Dict::set_dict_for_webform($ekitaldimotak, 'deitura', $obj->deitura);

        $form = new WebForm('/ekitaldiak/ekitaldia/guardar');
        $form->add_hidden('id', $obj->ekitaldi_id);
        $form->add_text('ekitaldi_izena', 'Ekitaldiaren Izena',$obj->ekitaldi_izena);
        $form->add_select('ekitaldimota', $ekitaldimotak, 'Ekitaldi Mota');
        $form->add_text('data', 'data', $obj->data);
        $form->add_text('ordua', 'ordua', $obj->ordua);
        $form->add_text('izena', 'Lekuaren Izena', $obj->izena);
        $form->add_text('helbidea', 'helbidea', $obj->helbidea);
        $form->add_text('herria', 'herria', $obj->helbidea);
        $form->add_submit('Ekitaldia gehitu');
        print Template('Ekitaldi berria')->show($form->show());
    }

    public function listar($coleccion=array()) {
        foreach ($coleccion as &$obj) {
            $obj->lekua = $obj->lekua->izena;
            $obj->ekitaldimota = $obj->ekitaldimota->deitura;
        }
        $tabla = CollectorViewer($coleccion, 'ekitaldiak',  'ekitaldia',
            False, True, True)->get_table();
        print Template('Ekitaldien zerrenda')->show($tabla);
    }


}

?>
