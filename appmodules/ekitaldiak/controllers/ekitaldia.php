<?php

import('appmodules.ekitaldiak.models.ekitaldia');
import('appmodules.ekitaldiak.views.ekitaldia');
import('appmodules.ekitaldiak.models.lekua');


class EkitaldiaController extends Controller {


    public function agregar($errores=array()) {
        $ekitaldimota_collector = CollectorObject::get('EkitaldiMota');
        $ekitaldimotak = $ekitaldimota_collector->collection;
        $this->view->agregar($ekitaldimotak, $errores);
    }

    public function editar($id=0) {
        $ekitaldimota_collector = CollectorObject::get('EkitaldiMota');
        $ekitaldimotak = $ekitaldimota_collector->collection;
        $this->model->ekitaldia_id = $id;
        $this->model->get();
        $this->view->editar($ekitaldimotak, $this->model);
    }

    private function lekuaGorde(){
        $lekua = new Lekua();
        $lekua->izena = get_data('izena');
        $lekua->herria = get_data('herria');
        $lekua->helbidea = get_data('helbidea');
        $lekua->save();
        return $lekua;
    }

    public function guardar() {

        $errores = array();

        $requeridos = array("data", "ordua", "ekitaldi_izena", "ekitaldimota", "izena");
        $errores= validar_requeridos($errores, $requeridos);

        $campoImagen = 'kartela';
        $tipo_permitido = array("image/png", "image/jpeg", "image/gif", "image/bmp", "image/jpg");
        $tipo = isset($_FILES['kartela']['type']) ? $_FILES['kartela']['type'] : "image/jpg";
        $errores= validar_tipoImagen($errores, $tipo_permitido, $tipo, $campoImagen);

        if($errores and get_data('id') == 0)  {$this->agregar($errores);exit;}
        if($errores and get_data('id') !== 0) {$this->editar($id, $errores);exit;}

        $lekua = $this->lekuaGorde();

        $this->model->ekitaldia_id = get_data('id');
        $this->model->lekua = Pattern::composite('Lekua', $lekua);
        $this->model->data = get_data('data');
        $this->model->izena = get_data('ekitaldi_izena');
        $this->model->ordua = get_data('ordua');
        $ekitaldimota = Pattern::factory('EkitaldiMota', get_data('ekitaldimota') );
        $this->model->ekitaldimota = Pattern::composite('EkitaldiMota', $ekitaldimota);
        $this->model->save();

        //ruta del src para ver la imagen /artxibo?dokumentua=/ekitaldiak/ekitaldia/kartelak/ekitaldia_id
        $ruta = WRITABLE_DIR . "/ekitaldiak/ekitaldia/kartelak/{$this->model->ekitaldia_id}";
        guardar_imagen($ruta, $campoImagen);

        HTTPHelper::go("/ekitaldiak/ekitaldia/listar");

    }

    public function listar() {
        $collection = CollectorObject::get('Ekitaldia');
        $list = $collection->collection;
        $this->view->listar($list);
    }

    public function eliminar($id=0) {
        $this->model->ekitaldia_id = $id;
        $this->model->destroy();
        HTTPHelper::go("/ekitaldiak/ekitaldia/listar");
    }

}

?>
