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

    public function guardar() {

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

        $tipo_permitido = array("image/png", "image/jpeg", "image/gif", "image/bmp", "image/jpg");
        $tipo = isset($_FILES['kartela']) ? $_FILES['kartela']['type'] : "image/bmp";

        if (!in_array($tipo, $tipo_permitido) OR $_FILES['kartela']['error'] > 1){
            $errores['kartela'] = "Formatua edo tamaina ez da egokia.";
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

        $ruta = WRITABLE_DIR . "/ekitaldiak/ekitaldia/kartelak/{$this->model->ekitaldia_id}";

        if(isset($_FILES['kartela']['tmp_name'])){
            move_uploaded_file($_FILES['kartela']['tmp_name'], $ruta);
        }
        //ruta del src para imagen /artxibo?dokumentua=/ekitaldiak/ekitaldia/kartelak/ekitaldia_id

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
