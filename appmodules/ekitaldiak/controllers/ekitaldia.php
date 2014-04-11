<?php

import('appmodules.ekitaldiak.models.ekitaldia');
import('appmodules.ekitaldiak.views.ekitaldia');


class EkitaldiaController extends Controller { 

    public function agregar() {
        $this->view->agregar();
    }

    public function editar($id=0) {
        $this->model->ekitaldia_id = $id;
        $this->model->get();
        $this->view->editar($this->model);
    }

    public function guardar() {
        $id = (isset($_POST['id'])) ? $_POST['id'] : 0;
        $this->model->ekitaldia_id = $id;
        # ...
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
