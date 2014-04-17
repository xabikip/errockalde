<?php

class EkitaldiaView {

    public function agregar($ekitaldimotak) {
        Dict::set_dict_for_webform($ekitaldimotak, 'deitura');

        $form = new WebForm('/ekitaldiak/ekitaldia/guardar');
        $form->add_text('izenaekitaldi', 'Ekitaldiaren Izena');
        $form->add_select('ekitaldimota', $ekitaldimotak, 'Ekitaldi Mota');
        $form->add_text('data');
        $form->add_text('ordua');
        $form->add_text('izena', 'Lekuaren Izena');
        $form->add_text('helbidea');
        $form->add_text('herria');
        $form->add_submit('Ekitaldia gehitu');
        $str = $form->show();
        print Template('Ekitaldia gehitu')->show($str);
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
