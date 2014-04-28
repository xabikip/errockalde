<?php

import('appmodules.eventos.models.evento');
import('appmodules.eventos.views.evento');


class EventoController extends Controller { 

    public function agregar() {
        $this->view->agregar();
    }

    public function editar($id=0) {
        $this->model->evento_id = $id;
        $this->model->get();
        $this->view->editar($this->model);
    }

    public function guardar() {
        $id = (isset($_POST['id'])) ? $_POST['id'] : 0;
        $this->model->evento_id = $id;
        # ...
        $this->model->save();
        HTTPHelper::go("/eventos/evento/listar");
    }
    
    public function listar() {
        $collection = CollectorObject::get('Evento');
        $list = $collection->collection;
        $this->view->listar($list);
    }

    public function eliminar($id=0) {
        $this->model->evento_id = $id;
        $this->model->destroy();
        HTTPHelper::go("/eventos/evento/listar");
    }

}

?>
