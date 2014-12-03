<?php

class kategoriaController extends Controller {

    public function agregar($errores=array()) {
        $level = 1; # Nivel de acceso mínimo requerido para el recurso
        @SessionHandler()->check_state($level);
        $this->view->agregar($errores);
    }

    public function editar($id=0, $errores=array()) {
        $level = 1; # Nivel de acceso mínimo requerido para el recurso
        @SessionHandler()->check_state($level);
        $this->model->kategoria_id = $id;
        $this->model->get();
        $this->view->editar($this->model, $errores);
    }

    public function guardar() {
        $level = 1; # Nivel de acceso mínimo requerido para el recurso
        @SessionHandler()->check_state($level);
        $id = get_data('id');

        $errores = array();
        $requeridos = array("deitura");
        validar_requeridos($errores, $requeridos);

        if($errores) {
            (!$id) ? $this->agregar($errores) : $this->editar($id, $errores);exit();
        }

        $this->model->kategoria_id = $id;
        $this->model->deitura = get_data('deitura');
        $this->model->save();

        HTTPHelper::go("/blog/kategoria/listar");
    }

    public function listar() {
        $collection = CollectorObject::get('kategoria');
        $list = $collection->collection;
        $this->view->listar($list);
    }

    public function eliminar($id=0) {
        $level = 1; # Nivel de acceso mínimo requerido para el recurso
        @SessionHandler()->check_state($level);
        $this->model->kategoria_id = $id;
        $this->model->destroy();
        HTTPHelper::go("/blog/kategoria/listar");
    }

}

?>
