<?php

class DiskoaController extends Controller {

    public function agregar($errores=array()) {
        $talde_collector = CollectorObject::get('Talde');
        $taldeak = $talde_collector->collection;
        $this->view->agregar($taldeak, $errores);
    }

    public function editar($id=0) {
        $this->model->diskoa_id = $id;
        $this->model->get();
        $this->view->editar($this->model);
    }

    public function guardar() {
        $id = get_data('id');

        $errores = $this->validaciones();
        if($errores) {
            (!$id) ? $this->agregar($errores) : $this->editar($id, $errores);
            exit();
        }

        $this->model->diskoa_id = $id;
        $this->model->izena = get_data('izena');
        $this->model->data = get_data('data');
        $this->model->iraupena = get_data('iraupena');
        $this->model->abestiak = get_data('abestiak');
        $this->model->taldea = get_data('taldea');
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

    # ==========================================================================
    #                       PRIVATE FUNCTIONS: Helpers
    # ==========================================================================

    private function validaciones(){
        $errores = array();

        $requeridos = array("izena");
        $errores = validar_requeridos($errores, $requeridos);

        $campoImagen = 'argazkia';
        $tipo_permitido = array("image/png", "image/jpeg", "image/gif",
            "image/bmp", "image/jpg");
        $errores= validar_tipoImagen($errores, $tipo_permitido, $campoImagen);

        return $errores;
    }


}

?>
