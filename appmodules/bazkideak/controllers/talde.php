<?php

import('appmodules.bazkideak.models.talde');
import('appmodules.bazkideak.views.talde');

class TaldeController extends Controller {

    public function agregar() {
        $this->view->agregar();
    }

    public function editar($id=0) {
        $this->model->talde_id = $id;
        $this->model->get();
        $this->view->editar($this->model);
    }

    public function guardar() {
        $id = (isset($_POST['id'])) ? $_POST['id'] : 0;

        $errores = array();
        $requeridos = array("izena", "emaila" );

        foreach ($requeridos as $key => $value) {
            $val = isset($_POST[$value]) ? $_POST[$value] : '';
            if(!$val) $errores[$value] = ''. $value . ' beharrezkoa da';
        }

        if($errores) {
            $this->view->agregar($errores);
        } else {
            $this->model->talde_id = $id;
            $this->model->izena = $_POST['izena'];
            $this->model->web = $_POST['web'];
            $this->model->emaila = $_POST['emaila'];
            $this->model->telefonoa = $_POST['telefonoa'];
            $this->model->save();
            HTTPHelper::go("/bazkideak/talde/listar");
        }
    }

    public function listar() {
        $collection = CollectorObject::get('Talde');
        $list = $collection->collection;
        $this->view->listar($list);
    }

    public function eliminar($id=0) {
        $this->model->talde_id = $id;
        $this->model->destroy();
        HTTPHelper::go("/bazkideak/talde/listar");
    }

}

?>
