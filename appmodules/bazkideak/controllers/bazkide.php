<?php

import('appmodules.bazkideak.models.bazkide');
import('appmodules.bazkideak.views.bazkide');

class BazkideController extends Controller {

    public function agregar() {
        $this->view->agregar();
    }

    public function editar($id=0) {
        $this->model->bazkide_id = $id;
        $this->model->get();
        $this->view->editar($this->model);
    }

    public function guardar() {
        $id = (isset($_POST['id'])) ? $_POST['id'] : 0;
        $this->model->bazkide_id = $id;
        $this->model->izena = $_POST['izena'];
        $this->model->abizena = $_POST['abizena'];
        $this->model->goitizena = $_POST['goitizena'];
        $this->model->emaila = $_POST['emaila'];
        $this->model->telefonoa = $_POST['telefonoa'];
        $this->model->save();
        HTTPHelper::go("/bazkideak/bazkide/listar");
    }

    public function listar() {
        $collection = CollectorObject::get('Bazkide');
        $list = $collection->collection;
        $this->view->listar($list);
    }

    public function eliminar($id=0) {
        $this->model->bazkide_id = $id;
        $this->model->destroy();
        HTTPHelper::go("/bazkideak/bazkide/listar");
    }

}

?>
