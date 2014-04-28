<?php

import('appmodules.eventos.models.lugar');
import('appmodules.eventos.views.lugar');


class LugarController extends Controller { 

    public function agregar() {
        $this->view->agregar();
    }

    public function editar($id=0) {
        $this->model->lugar_id = $id;
        $this->model->get();
        $this->view->editar($this->model);
    }

    public function guardar() {
        $id = (isset($_POST['id'])) ? $_POST['id'] : 0;
        $this->model->lugar_id = $id;
        # ...
        $this->model->save();
        HTTPHelper::go("/eventos/lugar/listar");
    }
    
    public function listar() {
        $collection = CollectorObject::get('Lugar');
        $list = $collection->collection;
        $this->view->listar($list);
    }

    public function eliminar($id=0) {
        $this->model->lugar_id = $id;
        $this->model->destroy();
        HTTPHelper::go("/eventos/lugar/listar");
    }

}

?>
