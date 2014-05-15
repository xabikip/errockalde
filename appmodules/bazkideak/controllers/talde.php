<?php

import('appmodules.bazkideak.models.talde');
import('appmodules.bazkideak.views.talde');

class TaldeController extends Controller {

    public function agregar($errores=array()) {
        $bazkide_collector = CollectorObject::get('Bazkide');
        $bazkideak = $bazkide_collector->collection;
        $this->view->agregar($bazkideak, $errores);
    }

    public function editar($id=0, $errores=array()) {
        $this->model->talde_id = $id;
        $this->model->get();
        $this->view->editar($this->model, $errores);
    }

    public function guardar() {

        $id = get_data('id');
        $izena = get_data('izena');
        $bazkideak = get_data('bazkideak');
        $web = get_data('web');
        $emaila = get_data('emaila');
        $telefonoa = get_data('telefonoa');

        $errores = array();
        $requeridos = array("izena", "emaila", "bazkideak" );

        foreach ($requeridos as $value) {
            if ( is_null($$value)) $errores[$value]  = "$value beharrezkoa da";
        }

        if(!$errores){
            if(!filter_var($_POST['emaila'], FILTER_VALIDATE_EMAIL)) $errores['emaila'] = 'Emaila ez da egokia';
        }

        if($errores and $id == 0) {$this->agregar($errores);exit;}

        if($errores and $id !== 0) {$this->editar($id, $errores);exit;}

        $this->model->talde_id = $id;
        $this->model->izena = $izena;
        $this->model->web = $web;
        $this->model->emaila = $emaila;
        $this->model->telefonoa = $telefonoa;
        $this->model->save();

        foreach ($bazkideak as $bazkide) {
            $this->model->add_bazkide(Pattern::factory('Bazkide', $bazkide));
        }

        $lc = new LogicalConnector($this->model, 'bazkide');
        $lc->save();
        HTTPHelper::go("/bazkideak/talde/listar");

    }

    public function listar() {
        $collection = CollectorObject::get('Talde');
        $list = $collection->collection;
        $this->view->listar($list);
    }

    public function eliminar($id=0) {
        $this->model->talde_id = $id;
        $this->model->destroy();
        HTTPHelper::go("/bazkideak/talde/listar");
    }

}

?>
