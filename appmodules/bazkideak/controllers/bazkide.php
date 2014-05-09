<?php

import('appmodules.bazkideak.models.bazkide');
import('appmodules.bazkideak.views.bazkide');

class BazkideController extends Controller {

    public function agregar($errores) {
        $this->view->agregar($errores);
    }

    public function editar($id=0, $errores=array()) {
        $this->model->bazkide_id = $id;
        $this->model->get();
        $this->view->editar($this->model, $errores);
    }

    public function guardar() {

        function get_data($campo){
            return isset($_POST[$campo]) ? $_POST[$campo] : null;
        }

        $id = get_data('id');
        $izena = get_data('izena');
        $abizena = get_data('abizena');
        $goitizena = get_data('goitizena');
        $emaila = get_data('emaila');
        $telefonoa = get_data('telefonoa');

        $errores = array();
        $requeridos = array("izena", "emaila" );

        foreach ($requeridos as $value) {
            if ($$value == null) $errores[$value]  = "$value beharrezkoa da";
        }

        if(!$errores){
            if(!filter_var($_POST['emaila'], FILTER_VALIDATE_EMAIL)) $errores['emaila'] = 'Emaila ez da egokia';
        }

        if($errores and $id == 0) {$this->agregar($errores);exit;}

        if($errores and $id !== 0) {$this->editar($id, $errores);exit;}

        $this->model->bazkide_id = $id;
        $this->model->izena = $izena;
        $this->model->abizena = $abizena;
        $this->model->goitizena = $goitizena;
        $this->model->emaila = $emaila;
        $this->model->telefonoa = $telefonoa;
        $this->model->save();
        HTTPHelper::go("/bazkideak/bazkide/listar");


    }

    public function listar() {
        $collection = CollectorObject::get('Bazkide');
        $list = $collection->collection;
        $this->view->listar($list);
    }

    public function eliminar($id=0) {
        $this->model->bazkide_id = $id;
        $this->model->destroy();
        HTTPHelper::go("/bazkideak/bazkide/listar");
    }

}

?>
