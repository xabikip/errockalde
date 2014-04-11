<?php

import('appmodules.ekitaldiak.models.lekua');
import('appmodules.ekitaldiak.views.lekua');


class LekuaController extends Controller { 

    public function agregar() {
        $this->view->agregar();
    }

    public function editar($id=0) {
        $this->model->lekua_id = $id;
        $this->model->get();
        $this->view->editar($this->model);
    }

    public function guardar() {
        $id = (isset($_POST['id'])) ? $_POST['id'] : 0;
        $this->model->lekua_id = $id;
        # ...
        $this->model->save();
        HTTPHelper::go("/ekitaldiak/lekua/listar");
    }
    
    public function listar() {
        $collection = CollectorObject::get('Lekua');
        $list = $collection->collection;
        $this->view->listar($list);
    }

    public function eliminar($id=0) {
        $this->model->lekua_id = $id;
        $this->model->destroy();
        HTTPHelper::go("/ekitaldiak/lekua/listar");
    }

}

?>
