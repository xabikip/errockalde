<?php

import('appmodules.ekitaldiak.models.ekitaldia');
import('appmodules.ekitaldiak.views.ekitaldia');
import('appmodules.ekitaldiak.models.lekua');


class EkitaldiaController extends Controller {


    public function agregar($errores=array()) {
        $level = 1; # Nivel de acceso mínimo requerido para el recurso
        @SessionHandler()->check_state($level);
        $ekitaldimota_collector = CollectorObject::get('EkitaldiMota');
        $ekitaldimotak = $ekitaldimota_collector->collection;

        $lekua_collector = CollectorObject::get('Lekua');
        $lekuak = $lekua_collector->collection;
        $e = ($errores) ? $errores : array();
        $this->view->agregar($ekitaldimotak, $lekuak, $e);
    }

    public function editar($id=0, $errores=array()) {
        $level = 1; # Nivel de acceso mínimo requerido para el recurso
        @SessionHandler()->check_state($level);
        $ekitaldimota_collector = CollectorObject::get('EkitaldiMota');
        $ekitaldimotak = $ekitaldimota_collector->collection;

        $lekua_collector = CollectorObject::get('Lekua');
        $lekuak = $lekua_collector->collection;

        $this->model->ekitaldia_id = $id;
        $this->model->get();

        $this->view->editar($this->model, $ekitaldimotak, $lekuak, $errores);
    }

    public function guardar() {
        $level = 1; # Nivel de acceso mínimo requerido para el recurso
        @SessionHandler()->check_state($level);

        $errores = $this->validaciones();

        $id = get_data('id');
        if($errores) {
            (!$id) ? $this->agregar($errores) : $this->editar($id, $errores);
            exit();
        }

        $lekua = $this->lekuaGorde();

        $this->model->ekitaldia_id = $id;

        $this->model->lekua = Pattern::composite('Lekua', $lekua);
        $this->model->deskribapena = get_data('deskribapena');
        $this->model->data = get_data('data');
        $this->model->izena = get_data('ekitaldi_izena');

        $slugger = new Slugger();
        $this->model->slug =  $slugger->slugify(get_data('ekitaldi_izena'));

        $this->model->ordua = get_data('ordua');

        $ekitaldimota = Pattern::factory('EkitaldiMota', get_data('ekitaldimota') );
        $this->model->ekitaldimota = Pattern::composite('EkitaldiMota', $ekitaldimota);

        $this->model->save();

        $ruta = WRITABLE_DIR . EKITALDI_IRUDI_DIR . "/{$this->model->ekitaldia_id}";
        $campoImagen = 'kartela';
        guardar_imagen($ruta, $campoImagen);

        HTTPHelper::go("/ekitaldiak/ekitaldia/listar");

    }

    public function listar() {
        $collection = CollectorObject::get('Ekitaldia');
        $list = $collection->collection;
        $this->view->listar($list);
    }

    public function ekitaldiak() {
        $atzo = date('Y-m-d', strtotime(' -1 day'));
        $list = DataHandler('ekitaldia', DH_FORMAT_OBJECT)->filter("data>" . $atzo, DH_FILTER_GT);
        $sort= SORT_ASC;
        $this->view->ekitaldiak($list, $sort);
    }

    public function ekitaldiak_artxibo() {
        $atzo = date('Y-m-d', strtotime(' -1 day'));
        $list = DataHandler('ekitaldia', DH_FORMAT_OBJECT)->filter("data<" . $atzo, DH_FILTER_LT);
        $sort= SORT_DESC;
        $this->view->ekitaldiak($list, $sort);
    }

    public function ekitaldia($id=0) {
        $event = DataHandler('ekitaldia')->filter("slug=$id[1]");
        $this->model->ekitaldia_id = $event[0]['ekitaldia_id'];
        $this->model->get();
        $this->view->ekitaldia($this->model);
    }

    public function eliminar($id=0) {
        $level = 1; # Nivel de acceso mínimo requerido para el recurso
        @SessionHandler()->check_state($level);
        $this->model->ekitaldia_id = $id;
        $imagen = WRITABLE_DIR . "/ekitaldiak/ekitaldia/kartelak/{$this->model->ekitaldia_id}";
        file_put_contents($imagen, '');
        $this->model->destroy();
        HTTPHelper::go("/ekitaldiak/ekitaldia/listar");
    }

    public function get_eventos() {
        $collection = CollectorObject::get('Ekitaldia');
        $list = $collection->collection;
        $this->apidata = $list;
    }

    public function get_ultimos_eventos() {
        $atzo = date('Y-m-d', strtotime(' -1 day'));
        $list = DataHandler('ekitaldia', DH_FORMAT_OBJECT)->filter("data>" . $atzo, DH_FILTER_GT);
        // $ultimos = array_slice($list, count($list)-2, 2);
        $this->apidata = $list;
    }


    # ==========================================================================
    #                       PRIVATE FUNCTIONS: Helpers
    # ==========================================================================

    private function validaciones(){
        $errores = array();

        $requeridos = array("data", "ordua", "ekitaldi_izena", "ekitaldimota", "izena");
        validar_requeridos($errores, $requeridos);

        $campoImagen = 'kartela';
        $mime_permitidos = array(
            "image/png", "image/jpeg", "image/gif", "image/bmp",
            "image/jpg", "image/tiff",
        );
        validar_tipoImagen($errores, $mime_permitidos, $campoImagen);

        $campoHora = "ordua";
        validar_hora($errores, $campoHora);

        return $errores;
    }

    private function lekuaGorde(){
        $izena = get_data('izena');
        $herria = get_data('herria');
        $helbidea = get_data('helbidea');
        $lekua = DataHandler('lekua', DH_FORMAT_OBJECT)->filter("izena=$izena");
        if(!$lekua){
            $lekua = new Lekua();
            $lekua->izena = get_data('izena');
            $lekua->herria = get_data('herria');
            $lekua->helbidea = get_data('helbidea');
            $lekua->save();
            return $lekua;
        }elseif($lekua[0]->herria !== $herria or $lekua[0]->helbidea !== $helbidea){
            $lekua = new Lekua();
            $lekua->izena = get_data('izena');
            $lekua->herria = get_data('herria');
            $lekua->helbidea = get_data('helbidea');
            $lekua->save();
            return $lekua;
        }
        return $lekua['0'];
    }
}

?>
