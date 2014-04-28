<?php

import('appmodules.eventos.models.tipoevento');
import('appmodules.eventos.views.tipoevento');


class TipoEventoController extends Controller { 

    public function agregar() {
        $this->view->agregar();
    }

    public function editar($id=0) {
        $this->model->tipoevento_id = $id;
        $this->model->get();
        $this->view->editar($this->model);
    }

    public function guardar() {
        $id = (isset($_POST['id'])) ? $_POST['id'] : 0;
        $this->model->tipoevento_id = $id;
        # ...
        $this->model->save();
        HTTPHelper::go("/eventos/tipoevento/listar");
    }
    
    public function listar() {
        $collection = CollectorObject::get('TipoEvento');
        $list = $collection->collection;
        $this->view->listar($list);
    }

    public function eliminar($id=0) {
        $this->model->tipoevento_id = $id;
        $this->model->destroy();
        HTTPHelper::go("/eventos/tipoevento/listar");
    }

}

?>
