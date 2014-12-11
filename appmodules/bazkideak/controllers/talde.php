<?php

import('appmodules.bazkideak.models.talde');
import('appmodules.bazkideak.views.talde');

class TaldeController extends Controller {

    public function agregar($errores=array()) {
        $level = 1; # Nivel de acceso mínimo requerido para el recurso
        @SessionHandler()->check_state($level);
        $bazkide_collector = CollectorObject::get('Bazkide');
        $bazkideak = $bazkide_collector->collection;
        $this->view->agregar($bazkideak, $errores);
    }

    public function editar($id=0, $errores=array()) {
        $level = 1; # Nivel de acceso mínimo requerido para el recurso
        @SessionHandler()->check_state($level);
        $bazkide_collector = CollectorObject::get('Bazkide');
        $bazkideak = $bazkide_collector->collection;
        $this->model->talde_id = $id;
        $this->model->get();
        $this->view->editar($this->model, $bazkideak, $errores);
    }

    public function guardar() {
        $level = 1; # Nivel de acceso mínimo requerido para el recurso
        @SessionHandler()->check_state($level);
        $id = get_data('id');
        $slug = ($id == 0) ? TaldeHelper::get_slug() : get_data('customurl');

        $errores = $this->validaciones();
        if($errores) {
            (!$id) ? $this->agregar($errores) : $this->editar($id, $errores);
            exit();
        }

        $this->model->talde_id = $id;
        $this->model->izena = get_data('izena');
        $this->model->web = get_data('web');
        $this->model->emaila = get_data('emaila');
        $this->model->telefonoa = get_data('telefonoa');
        $this->model->deskribapena = get_data('deskribapena');
        $this->model->customurl = $slug;
        $this->model->save();

        $this->__set_aditional_properties();

        if (get_data('youtube') !== "") $this->guardar_youtube();

        $bazkideak = get_data('bazkideak');
        $this->guardar_bazkide($bazkideak);

        $campoImagen = 'argazkia';
        guardar_imagen($this->imagen, $campoImagen);

        $this->view->añadir_disco($this->model);

    }

    public function listar() {
        $level = 1; # Nivel de acceso mínimo requerido para el recurso
        @SessionHandler()->check_state($level);
        $collection = CollectorObject::get('Talde');
        $list = $collection->collection;
        $this->view->listar($list);
    }

    public function taldeak() {
        $collection = CollectorObject::get('Talde');
        $list = $collection->collection;
        $this->view->taldeak($list);
    }

    public function taldea($customurl=''){
        $taldea = DataHandler('talde')->filter("customurl=$customurl[1]");
        $this->model->talde_id = $taldea[0]['talde_id'];
        $this->model->get();
        $this->view->taldea($this->model);
    }

    #FIXME creame un modulo
    public function hasiera() {
        $collection_talde = CollectorObject::get('Talde');
        $taldeak = $collection_talde->collection;

        $ruta = SERVER_URI ."/api/ekitaldiak/ekitaldia/get-ultimos-eventos";
        $json = file_get_contents($ruta);
        $ekitaldiak = json_decode($json);

        $ruta = SERVER_URI ."/api/blog/post/get-ultimos-posts";
        $json = file_get_contents($ruta);
        $posts = json_decode($json);

        $this->view->hasiera($taldeak, $ekitaldiak, $posts);
    }

    #FIXME creame un modulo
    public function kontaktua($result='') {
        $result = ($result == 'kontaktua') ? '' : $result;
        $error = 0;
        $this->view->kontaktua($error, $result);
    }

    #FIXME creame un modulo
    public function formbidali() {
        $errores = $this->validar_contact();
        $error = 1;
        if($errores) exit(
            $this->view->kontaktua($error, "Emaila eta izena beharrezkoak dira"));
        $msg = EuropioCode::decode(get_data('mezua'));

        if(PRODUCTION) {
            $emaila = get_data('emaila');
            $izena = get_data('izena');
            $mail_to = "Xabi <xabikip@gmail.com>";
            $mail_head  = "MIME-Version: 1.0\r\n";
            $mail_head .= "Content-type: text/html; charset=utf-8\r\n";
            $mail_head .= "To: {$mail_to}\r\n";
            $mail_head .= "From: {$izena} <{$emaila}>\r\n";
            $mail_head .= "Reply-To: {$izena} <{$emaila}>\r\n";
            mail($mail_to, "MusikaGunea kontaktua", $msg, $mail_head);
            $error = 0;
            $this->view->kontaktua($error, "Mezua bidali da. Ahal bezain azkarren erantzungo dizugu");
        }else{
            $this->view->kontaktua($error, "ERROREA(CONFG.INI->PRODUCTION:FALSE)" );
        }
    }

    public function eliminar($id=0) {
        $level = 1; # Nivel de acceso mínimo requerido para el recurso
        @SessionHandler()->check_state($level);
        $this->model->talde_id = (int)$id;
        $this->__set_aditional_properties();
        $this->model->destroy();
        $this->eliminar_archivos();
        HTTPHelper::go("/bazkideak/talde/listar");
    }

    public function __call($funtzioa, $argumentuak=array()) {
        $ini = "/{$this->model->talde_id}.ini";
        $img = "/{$this->model->talde_id}";
        $this->imagen = WRITABLE_DIR . IRUDI_DIR . $img;
        $this->bandcamp =  WRITABLE_DIR . BANDCAMP_DIR . $ini;
        $this->youtube = WRITABLE_DIR . YOUTUBE_DIR . $ini;
    }

    # ==========================================================================
    #                       PRIVATE FUNCTIONS: Helpers
    # ==========================================================================

    private function validaciones(){
        $errores = array();

        $requeridos = array("izena", "emaila", "bazkideak" );
        validar_requeridos($errores, $requeridos);

        $campoMail = 'emaila';
        validar_formato_mail($errores, $campoMail);

        $campoImagen = 'argazkia';
        $tipo_permitido = array("image/png", "image/jpeg", "image/gif",
            "image/bmp", "image/jpg");
        validar_tipoImagen($errores, $tipo_permitido, $campoImagen);

        return $errores;
    }

    private function validar_contact(){
        $errores = array();

        $requeridos = array("emaila", "mezua");
        $errores = validar_requeridos($errores, $requeridos);

        $campoMail = 'emaila';
        $errores = validar_formato_mail($errores, $campoMail);

        return $errores;
    }


    private function guardar_bazkide($bazkideak){
        foreach ($bazkideak as $bazkide) {
            $this->model->add_bazkide(Pattern::factory('Bazkide', $bazkide));
        }
        $lc = new LogicalConnector($this->model, 'bazkide');
        $lc->save();
    }


    private function parse_video_id($youtube_decode){
        if(strpos($youtube_decode, "&#38;") > 0){
            $array_amp = explode("&#38;", $youtube_decode);
            $youtube_decode = $array_amp[0];
        }
        $youtube_partes = explode("v&#61;", $youtube_decode);
        if (count($youtube_partes) > 0) $video_id = array_pop($youtube_partes);
        return $video_id;
    }

    private function guardar_youtube(){
        $youtube_encode = isset($_POST['youtube']) ? EuropioCode::encode($_POST['youtube']) : '';
        $youtube_decode = EuropioCode::decode($youtube_encode);

        $video_id = $this->parse_video_id($youtube_decode);

        $allowed_chars = array("-" => "&#45;", "_" => "&#95;", " " => "&#160;");
        $video_id = str_replace(array_values($allowed_chars),
            array_keys($allowed_chars), $video_id);

        $contenido = "[youtube]\n v = $video_id";
        file_put_contents($this->youtube, $contenido);
    }

    private function eliminar_archivos(){
        file_put_contents($this->imagen, '');
        file_put_contents($this->bandcamp, '');
        file_put_contents($this->youtube, '');
    }

}

?>
