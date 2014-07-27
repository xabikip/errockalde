<?php

class kategoriaController extends Controller {

    public function agregar($errores=array()) {
        $this->view->agregar($errores);
    }

    public function editar($id=0, $errores=array()) {
        $this->model->kategoria_id = $id;
        $this->model->get();
        $this->view->editar($this->model, $errores);
    }

    public function guardar() {
        $id = get_data('id');

        $errores = array();
        $requeridos = array("deitura");
        $errores = validar_requeridos($errores, $requeridos);

        if($errores) {
            (!$id) ? $this->agregar($errores) : $this->editar($id, $errores);exit();
        }

        $this->model->kategoria_id = $id;
        $this->model->deitura = get_data('deitura');
        // print_r($this->model);exit;
        $this->model->save();

        HTTPHelper::go("/blog/kategoria/listar");
    }

    public function listar() {
        $collection = CollectorObject::get('kategoria');
        $list = $collection->collection;
        $this->view->listar($list);
    }

    public function eliminar($id=0) {
        $this->model->kategoria_id = $id;
        $this->model->destroy();
        HTTPHelper::go("/blog/kategoria/listar");
    }

}

?>
