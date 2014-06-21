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

    private function crear_slug(){
        $slugger = new Slugger();
        $customurl = $slugger->slugify(get_data('izena'));
        $this->model->customurl = $customurl;
    }

    private function parse_album_id($albumpartes){
        if (count($albumpartes) > 0) $albumidpartes = explode("&#47;", $albumpartes[1]);
        if (count($albumidpartes) > 0) $album_id = $albumidpartes[0];
        return $album_id;
    }

    private function parse_talde_slug($albumpartes){
        if (count($albumpartes) > 0) $talde_slug_partes = explode("&#47;&#47;", $albumpartes[1]);
        if (count($talde_slug_partes) > 0) $talde_slug_partes2 = explode("&", $talde_slug_partes[1]);
        if (count($talde_slug_partes2) > 0) $talde_slug = $talde_slug_partes2[0];
        return $talde_slug;
    }

    private function parse_album_slug($albumpartes){
        if (count($albumpartes) > 0) $album_slug_partes = explode("&#38;&#35;34&#59;&#38;&#35;62&#59;", $albumpartes[1]);
        if (count($album_slug_partes) > 0) $album_slug_partes2 = explode("&#47;", $album_slug_partes[0]);
        $album_slug = array_pop($album_slug_partes2);
        return $album_slug;
    }

    private function parse_album($albumpartes){
        if (count($albumpartes) > 0) $album_partes = explode("&#160;by&#160;", $albumpartes[1]);
        if (count($album_partes) > 0) $album_partes2 = explode("&#38;&#35;34&#59;&#38;&#35;62&#59;", $album_partes[0]);
        $album = array_pop($album_partes2);
        return $album;
    }

    private function parse_grupo($albumpartes){
        if (count($albumpartes) > 0) $grupo_partes = explode("&#160;by&#160;", $albumpartes[1]);
        if (count($grupo_partes) > 0) $grupo_partes2 = explode("&#38;&#35;60&#59;&#47;", $grupo_partes[1]);
        $grupo = array_shift($grupo_partes2);
        return $grupo;
    }

    private function guardar_bandcamp($id){

        $bandcamp_encode = isset($_POST['bandcamp']) ? EuropioCode::encode($_POST['bandcamp']) : '';

        $bandcamp_decode = EuropioCode::decode($bandcamp_encode);

        $albumpartes = explode("album&#61;", $bandcamp_decode);

        $album_id = $this->parse_album_id($albumpartes);
        $talde_slug = $this->parse_talde_slug($albumpartes);
        $album_slug = $this->parse_album_slug($albumpartes);
        $album = $this->parse_album($albumpartes);
        $grupo = $this->parse_grupo($albumpartes);

        $allowed_chars = array("-" => "&#45;", "_" => "&#95;", " " => "&#160;");

        $talde_slug = str_replace(array_values($allowed_chars), array_keys($allowed_chars), $talde_slug);
        $album_slug = str_replace(array_values($allowed_chars), array_keys($allowed_chars), $album_slug);

        $nombre_archivo = WRITABLE_DIR . "/bazkideak/taldea/bandcamp/$id.ini";

        $contenido = "[bandcamp]
album_id = \"$album_id\"
talde_slug= \"$talde_slug\"
album_slug= \"$album_slug\"
album= \"$album\"
grupo= \"$grupo\"";

        file_put_contents($nombre_archivo, $contenido);
    }

    private function parse_video_id($youtube_decode){
        $youtube_partes = explode("&#61;", $youtube_decode);
        if (count($youtube_partes) > 0) $video_id = array_pop($youtube_partes);
        return $video_id;
    }

    private function guardar_youtube($id){

        $youtube_encode = isset($_POST['youtube']) ? EuropioCode::encode($_POST['youtube']) : '';

        $youtube_decode = EuropioCode::decode($youtube_encode);

        $video_id = $this->parse_video_id($youtube_decode);

        $allowed_chars = array("-" => "&#45;", "_" => "&#95;", " " => "&#160;");

        $video_id = str_replace(array_values($allowed_chars), array_keys($allowed_chars), $video_id);

        $nombre_archivo = WRITABLE_DIR . "/bazkideak/taldea/youtube/$id.ini";

        $contenido = "[youtube]
v= \"$video_id\"";

        file_put_contents($nombre_archivo, $contenido);
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

        get_data('id') == 0 ? $this->crear_slug() : $this->model->customurl = get_data('customurl');

        $this->model->talde_id = get_data('id');
        $this->model->izena = get_data('izena');
        $this->model->web = get_data('web');
        $this->model->emaila = get_data('emaila');
        $this->model->telefonoa = get_data('telefonoa');
        $this->model->deskribapena = get_data('deskribapena');
        $this->model->save();

        if (get_data('bandcamp') !== "") $this->guardar_bandcamp($this->model->talde_id);

        if (get_data('youtube') !== "") $this->guardar_youtube($this->model->talde_id);

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
        $taldea = DataHandler('talde')->filter("customurl=$id[1]");
        $this->model->talde_id = $taldea[0]['talde_id'];
        $this->model->get();
        $this->view->taldea($this->model);
    }

    public function hasiera() {
        $collection_talde = CollectorObject::get('Talde');
        $taldeak = $collection_talde->collection;
        $ruta = SERVER_URI ."/api/ekitaldiak/ekitaldia/get-eventos";
        $json = file_get_contents($ruta);
        $ekitaldiak = json_decode($json);
        $this->view->hasiera($taldeak, $ekitaldiak);
    }

    private function eliminar_archivos($id){
        $imagen = WRITABLE_DIR . "/bazkideak/taldea/irudiak/{$this->model->talde_id}";
        unlink($imagen);
        $bandcamp = WRITABLE_DIR . "/bazkideak/taldea/bandcamp/$id.ini";
        unlink($bandcamp);
        $youtube = WRITABLE_DIR . "/bazkideak/taldea/youtube/$id.ini";
        unlink($youtube);
    }

    public function eliminar($id=0) {
        $this->model->talde_id = $id;
        $this->eliminar_archivos($this->model->talde_id);
        $this->model->destroy();
        HTTPHelper::go("/bazkideak/talde/listar");
    }

}

?>
