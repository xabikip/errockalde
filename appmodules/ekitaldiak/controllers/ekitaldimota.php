<?php

import('appmodules.ekitaldiak.models.ekitaldimota');
import('appmodules.ekitaldiak.views.ekitaldimota');


class EkitaldiMotaController extends Controller {

    public function agregar() {
        $this->view->agregar();
    }

    public function editar($id=0, $errores=array()) {
        $this->model->ekitaldimota_id = $id;
        $this->model->get();
        $this->view->editar($this->model, $errores);
    }

    public function guardar() {

        $errores = array();

        $requeridos = array("deitura");
        $errores= validar_requeridos($errores, $requeridos);

        if($errores and get_data('id') == 0) {$this->agregar($errores);exit;}
        if($errores and get_data('id') !== 0) {$this->editar($id, $errores);exit;}

        $this->model->ekitaldimota_id = get_data('id');;
        $this->model->deitura = get_data('deitura');
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

        $ruta = SERVER_URI ."/api/ekitaldiak/ekitaldia/get-eventos";
        $json = file_get_contents($ruta);

        $ekitaldiak = DataHandler('ekitaldia')->filter("ekitaldimota=$id");

        if (count($ekitaldiak) != 0) {
            $this->view->eventos_a_borrar($ekitaldiak);
        }else{
            $this->model->destroy();
            HTTPHelper::go("/ekitaldiak/ekitaldimota/listar");
        }

    }

    public function ezabatu_ekitaldiekin() {

        $id = get_data('ekitaldimota');

        $this->model->ekitaldimota_id = $id;

        $ekitaldiak = DataHandler('ekitaldia')->filter("ekitaldimota=$id");

        foreach ($ekitaldiak as $ekitaldia) {
            $obj = new Ekitaldia();
            $obj->ekitaldia_id = $ekitaldia['ekitaldia_id'];
            $obj->destroy();
        }

        $this->model->destroy();

        HTTPHelper::go("/ekitaldiak/ekitaldimota/listar");

    }

}

?>
