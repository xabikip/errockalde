<?php

class DiskoaController extends Controller { 

    public function agregar() {
        $this->view->agregar();
    }

    public function editar($id=0) {
        $this->model->diskoa_id = $id;
        $this->model->get();
        $this->view->editar($this->model);
    }

    public function guardar() {
        $id = (isset($_POST['id'])) ? $_POST['id'] : 0;
        $this->model->diskoa_id = $id;
        # ...
        $this->model->save();
        HTTPHelper::go("/bazkideak/diskoa/listar");
    }
    
    public function listar() {
        $collection = CollectorObject::get('Diskoa');
        $list = $collection->collection;
        $this->view->listar($list);
    }

    public function eliminar($id=0) {
        $this->model->diskoa_id = $id;
        $this->model->destroy();
        HTTPHelper::go("/bazkideak/diskoa/listar");
    }

}

?>
