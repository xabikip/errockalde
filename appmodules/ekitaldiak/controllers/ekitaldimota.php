<?php

import('appmodules.ekitaldiak.models.ekitaldimota');
import('appmodules.ekitaldiak.views.ekitaldimota');


class EkitaldiMotaController extends Controller {

    public function agregar() {
        $this->view->agregar();
    }

    public function editar($id=0) {
        $this->model->ekitaldimota_id = $id;
        $this->model->get();
        $this->view->editar($this->model);
    }

    public function guardar() {

        function get_data($campo){
            return isset($_POST[$campo]) ? $_POST[$campo] : null;
        }

        $id = get_data('id');
        $deitura = get_data('deitura');

        $errores = array();
        $requeridos = array("deitura");

        foreach ($requeridos as $value) {
            if ($$value == null) $errores[$value]  = "$value beharrezkoa da";
        }

        if($errores) { $this->view->agregar($errores);next;}

        $this->model->ekitaldimota_id = $id;
        $this->model->deitura = $_POST['deitura'];
        $this->model->save();
        HTTPHelper::go("/ekitaldiak/ekitaldimota/listar");
    }

    public function listar() {
        $collection = CollectorObject::get('EkitaldiMota');
        $list = $collection->collection;
        $this->view->listar($list);
    }

    public function eliminar($id=0) {
        $this->model->ekitaldimota_id = $id;
        $this->model->destroy();
        HTTPHelper::go("/ekitaldiak/ekitaldimota/listar");
    }

}

?>
