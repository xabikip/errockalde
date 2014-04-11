<?php

class EkitaldiaView {

    public function agregar() {
        $this->prepare_collection($ekitaldimotak, 'deitura');

        $form = new WebForm('/ekitaldiak/ekitaldia/guardar');
        $form->add_text('izena', 'Izena');
        $form->add_select('ekitaldi mota', $ekitaldimotak);
        $form->add_text('data', 'Data');
        $form->add_text('ordua', 'Ordua');
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
        $str = file_get_contents(
            STATIC_DIR . "html/ekitaldiak/ekitaldia_listar.html");
        $html = Template($str)->render_regex('LISTADO', $coleccion);
        print Template('Listado de Ekitaldia')->show($html);
    }

    private function prepare_collection(&$coleccion, $text) {
        $idproperty = strtolower(get_class($coleccion[0])) . "_id";
        foreach($coleccion as &$obj) {
            $obj->value = $obj->$idproperty;
            $obj->text = $obj->$text;
        }
    }


}

?>
