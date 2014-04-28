<?php

class EkitaldiaView {

    public function agregar($ekitaldimotak, $errores = array()) {
        Dict::set_dict_for_webform($ekitaldimotak, 'deitura', @$_POST['deitura']);

        $form = new WebForm('/ekitaldiak/ekitaldia/guardar');
        $form->add_text('ekitaldi_izena', 'Ekitaldiaren Izena',@$_POST['izena']);
        $form->add_select('ekitaldimota', $ekitaldimotak, 'Ekitaldi Mota');
        $form->add_text('data', 'data', @$_POST['data']);
        $form->add_text('ordua', 'ordua', @$_POST['ordua']);
        $form->add_text('izena', 'Lekuaren Izena', @$_POST['izena']);
        $form->add_text('helbidea', 'helbidea', @$_POST['helbidea']);
        $form->add_text('herria', 'herria', @$_POST['helbidea']);
        $form->add_submit('Ekitaldia gehitu');
        $str = $form->show();
        $form->add_error_zone($errores);
        print Template('Ekitaldi berria')->show($str);
    }

    public function editar($obj=array()) {
        $str = file_get_contents(
            STATIC_DIR . "html/ekitaldiak/ekitaldia_editar.html");
        $html = Template($str)->render($obj);
        print Template('Editar Ekitaldia')->show($html);
    }

    public function listar($coleccion=array()) {
        foreach ($coleccion as &$obj) {
            $obj->lekua = $obj->lekua->izena;
            $obj->ekitaldimota = $obj->ekitaldimota->deitura;
        }
        $tabla = CollectorViewer($coleccion, 'ekitaldiak',  'ekitaldia',
            False, True, True)->get_table();
        print Template('Listado de Ekitaldia')->show($tabla);
    }


}

?>
