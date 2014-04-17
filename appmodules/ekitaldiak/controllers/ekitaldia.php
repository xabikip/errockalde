<?php

import('appmodules.ekitaldiak.models.ekitaldia');
import('appmodules.ekitaldiak.views.ekitaldia');
import('appmodules.ekitaldiak.models.lekua');


class EkitaldiaController extends Controller {

    public function agregar() {
        $ekitaldimota_collector = CollectorObject::get('EkitaldiMota');
        $ekitaldimotak = $ekitaldimota_collector->collection;
        $this->view->agregar($ekitaldimotak);
    }

    public function editar($id=0) {
        $this->model->ekitaldia_id = $id;
        $this->model->get();
        $this->view->editar($this->model);
    }

    public function guardar() {
        $id = (isset($_POST['id'])) ? $_POST['id'] : 0;
        $ekitaldimota = Pattern::factory('EkitaldiMota', $_POST['ekitaldimota']);
        $lekua = new Lekua();
        $lekua->izena = $_POST['izena'];
        $lekua->herria = $_POST['herria'];
        $lekua->helbidea = $_POST['helbidea'];
        $lekua->save();
        $this->model->ekitaldia_id = $id;
        $this->model->lekua = Pattern::composite('Lekua', $lekua);
        $this->model->data = $_POST['data'];
        $this->model->izena = $_POST['izenaekitaldi'];
        $this->model->ordua = $_POST['ordua'];
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