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
        $bazkide_collector = CollectorObject::get('Bazkide');
        $bazkideak = $bazkide_collector->collection;
        $this->model->talde_id = $id;
        $this->model->get();
        $this->view->editar($this->model, $bazkideak, $errores);
    }

    private function guardar_bazkide($bazkideak){
        foreach ($bazkideak as $bazkide) {
            $this->model->add_bazkide(Pattern::factory('Bazkide', $bazkide));
        }
        $lc = new LogicalConnector($this->model, 'bazkide');
        $lc->save();
    }

    public function guardar() {

        $errores = array();

        $requeridos = array("izena", "emaila", "bazkideak" );
        $errores = validar_requeridos($errores, $requeridos);

        $campoMail = 'emaila';
        $errores = validar_formato_mail($errores, $campoMail);

        $campoImagen = 'argazkia';
        $tipo_permitido = array("image/png", "image/jpeg", "image/gif", "image/bmp", "image/jpg");
        $errores= validar_tipoImagen($errores, $tipo_permitido, $campoImagen);

        if($errores and get_data('id') == 0) {$this->agregar($errores);exit;}
        if($errores and get_data('id') !== 0) {$this->editar(get_data('id'), $errores);exit;}

        $this->model->talde_id = get_data('id');
        $this->model->izena = get_data('izena');
        $this->model->web = get_data('web');
        $this->model->emaila = get_data('emaila');
        $this->model->telefonoa = get_data('telefonoa');
        $this->model->save();

        $bazkideak = get_data('bazkideak');
        $this->guardar_bazkide($bazkideak);

        $ruta = WRITABLE_DIR . "/bazkideak/taldea/irudiak/{$this->model->talde_id}";
        guardar_imagen($ruta, $campoImagen);

        HTTPHelper::go("/bazkideak/talde/listar");

    }

    public function listar() {
        $collection = CollectorObject::get('Talde');
        $list = $collection->collection;
        $this->view->listar($list);
    }

    public function taldeak() {
        $collection = CollectorObject::get('Talde');
        $list = $collection->collection;
        $this->view->taldeak($list);
    }

    public function taldea($id=0) {
        $bazkide_collector = CollectorObject::get('Bazkide');
        $bazkideak = $bazkide_collector->collection;
        $this->model->talde_id = $id;
        $this->model->get();
        $this->view->taldea($this->model, $bazkideak);
    }

    public function hasiera() {
        $collection = CollectorObject::get('Talde');
        $taldeak = $collection->collection;
        $ruta = SERVER_URI ."/api/ekitaldiak/ekitaldia/get-eventos";
        $json = file_get_contents($ruta);
        $ekitaldiak = json_decode($json);
        $this->view->hasiera($taldeak, $ekitaldiak);
    }

    public function eliminar($id=0) {
        $this->model->talde_id = $id;
        $this->model->destroy();
        HTTPHelper::go("/bazkideak/talde/listar");
    }

}

?>
