<?php

class DiskoaController extends Controller {

    public function agregar($errores=array()) {
        $level = 1; # Nivel de acceso mínimo requerido para el recurso
        @SessionHandler()->check_state($level);
        $talde_collector = CollectorObject::get('Talde');
        $taldeak = $talde_collector->collection;
        $this->view->agregar($taldeak, $errores);
    }

    public function editar($id=0, $errores=array()) {
        $level = 1; # Nivel de acceso mínimo requerido para el recurso
        @SessionHandler()->check_state($level);
        $this->model->diskoa_id = $id;
        $this->model->get();

        $talde_collector = CollectorObject::get('Talde');
        $taldeak = $talde_collector->collection;

        $this->view->editar($this->model, $taldeak, $errores);
    }

    public function guardar() {
        $level = 1; # Nivel de acceso mínimo requerido para el recurso
        @SessionHandler()->check_state($level);
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
        $this->model->talde = get_data('taldea');
        $this->model->save();

        $this->__set_aditional_properties();

        if (get_data('bandcamp') !== "") $this->guardar_bandcamp();

        if (get_data('abestiak') !== "") $this->guardar_abestiak();

        $campoImagen = 'azala';
        guardar_imagen($this->imagen, $campoImagen);

        $this->view->preguntar($this->model);

    }

    public function listar() {
        $level = 1; # Nivel de acceso mínimo requerido para el recurso
        @SessionHandler()->check_state($level);
        $collection = CollectorObject::get('Diskoa');
        $list = $collection->collection;
        $this->view->listar($list);
    }

    public function eliminar($id=0) {
        $level = 1; # Nivel de acceso mínimo requerido para el recurso
        @SessionHandler()->check_state($level);
        $this->model->diskoa_id = $id;
        $this->model->destroy();
        HTTPHelper::go("/bazkideak/diskoa/listar");
    }

    public function __call($funtzioa, $argumentuak=array()) {
        $ini = "/{$this->model->diskoa_id}.ini";
        $id = "/{$this->model->diskoa_id}";
        $this->imagen = WRITABLE_DIR . AZALA_DIR . $id;
        $this->abestiak =  WRITABLE_DIR . ABESTIAK_DIR . $id;
        $this->bandcamp =  WRITABLE_DIR . BANDCAMP_DIR . $ini;
    }

    # ==========================================================================
    #                       PRIVATE FUNCTIONS: Helpers
    # ==========================================================================

    private function validaciones(){
        $errores = array();

        $requeridos = array("izena");
        validar_requeridos($errores, $requeridos);

        $campoImagen = 'argazkia';
        $tipo_permitido = array("image/png", "image/jpeg", "image/gif",
            "image/bmp", "image/jpg");
        validar_tipoImagen($errores, $tipo_permitido, $campoImagen);

        return $errores;
    }

     private function parse_album_id($albumpartes){
        if (count($albumpartes) > 0) $albumidpartes = explode("&#47;", $albumpartes[1]);
        if (count($albumidpartes) > 0) $album_id = $albumidpartes[0];
        return $album_id;
    }

    private function guardar_bandcamp(){
        $bandcamp_encode = isset($_POST['bandcamp']) ? EuropioCode::encode($_POST['bandcamp']) : '';
        $bandcamp_decode = EuropioCode::decode($bandcamp_encode);

        $albumpartes = explode("album&#61;", $bandcamp_decode);

        $album_id = $this->parse_album_id($albumpartes);
        $talde_slug = TaldeHelper::parse($albumpartes, SLASH_DBL, AMP);
        $album_slug = TaldeHelper::parse($albumpartes, FINAL_TAG, SLASH, 0);
        $album = TaldeHelper::parse($albumpartes, BY, FINAL_TAG, 0);
        $grupo = TaldeHelper::parse($albumpartes, BY, FINAL_ENDTAG);

        $allowed_chars = array("-" => "&#45;", "_" => "&#95;", " " => "&#160;");
        $talde_slug = str_replace(array_values($allowed_chars),
            array_keys($allowed_chars), $talde_slug);
        $album_slug = str_replace(array_values($allowed_chars),
            array_keys($allowed_chars), $album_slug);

        $contenido = "[bandcamp]
album_id = \"$album_id\"
talde_slug= \"$talde_slug\"
album_slug= \"$album_slug\"
album= \"$album\"
grupo= \"$grupo\"";
        file_put_contents($this->bandcamp, $contenido);
    }

    private function guardar_abestiak(){
        $abestiak_encode = isset($_POST['abestiak']) ? EuropioCode::encode($_POST['abestiak']) : '';
        $abestiak_decode = EuropioCode::decode($abestiak_encode);
        file_put_contents($this->abestiak, $abestiak_decode);
    }


}

?>
