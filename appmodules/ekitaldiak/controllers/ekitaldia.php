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
        $this->model->ekitaldia_id = $id;
        $this->model->get();
        $this->view->editar($this->model);
    }

    public function guardar() {

        function get_data($campo){
            return isset($_POST[$campo]) ? $_POST[$campo] : null;
        }

        $id = get_data('id');
        $ekitaldimota = get_data('ekitaldimota');
        $data = get_data('data');
        $izena = get_data('izena');
        $herria = get_data('herria');
        $helbidea = get_data('helbidea');
        $ordua = get_data('ordua');
        $ekitaldi_izena = get_data('ekitaldi_izena');

        $errores = array();
        $requeridos = array("data", "ordua", "ekitaldi_izena", "ekitaldimota", "izena");

        foreach ($requeridos as $value) {
            if ($$value == null) $errores[$value]  = "$value beharrezkoa da";
        }

        if($errores)  {$this->agregar($errores);exit;}

        $lekua = new Lekua();
        $lekua->izena = $izena;
        $lekua->herria = $herria;
        $lekua->helbidea = $helbidea;
        $lekua->save();

        $this->model->ekitaldia_id = $id;
        $this->model->lekua = Pattern::composite('Lekua', $lekua);
        $this->model->data = $data;
        $this->model->izena = $ekitaldi_izena;
        $this->model->ordua = $ordua;
        $ekitaldimota = Pattern::factory('EkitaldiMota', $ekitaldimota );
        $this->model->ekitaldimota = Pattern::composite('EkitaldiMota', $ekitaldimota);
        $this->model->save();
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
