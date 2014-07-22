<?php

class postController extends Controller {

    public function agregar($errores=array()) {
        $this->view->agregar($errores);
    }

    public function editar($id=0, $errores=array()) {
        $this->model->post_id = $id;
        $this->model->get();
        $this->view->editar($this->model);
    }

    public function guardar() {
        $id = get_data('id');

        $errores = $this->validaciones();

        if($errores) {
            (!$id) ? $this->agregar($errores) : $this->editar($id, $errores);exit();
        }

        $this->model->post_id = $id;
        $this->model->titularra = get_data('titularra');;
        $this->model->sortua = date('Y-m-d');
        $this->model->aldatua = date('Y-m-d');
        $this->model->save();

        $this->__set_aditional_properties();

        if (get_data('parrafoa') !== "") $this->guardar_parrafoa();
        if (get_data('edukia') !== "") $this->guardar_edukia();

        $campoImagen = 'irudia';
        guardar_imagen($this->irudia, $campoImagen);

        HTTPHelper::go("/blog/post/listar");
    }

    public function listar() {
        $collection = CollectorObject::get('post');
        $list = $collection->collection;
        $this->view->listar($list);
    }

    public function eliminar($id=0) {
        $this->model->post_id = $id;
        $this->model->destroy();
        HTTPHelper::go("/blog/post/listar");
    }

    public function post($id=0) {
        $post = DataHandler('post')->filter("post_id=$id");
        $this->model->post_id = $post[0]['post_id'];
        $this->model->get();
        $this->view->post($this->model);
    }

    public function posts() {
        $collection = CollectorObject::get('Post');
        $list = $collection->collection;
        $this->view->posts($list);
    }

    public function get_posts() {
        $collection = CollectorObject::get('Post');
        $list = $collection->collection;
        $this->apidata = $list;
    }

    public function __call($funtzioa, $argumentuak=array()) {
        $txt= "/{$this->model->post_id}.txt";
        $id= "/{$this->model->post_id}";
        $this->irudia = WRITABLE_DIR . POST_IRUDI_DIR . $id;
        $this->parrafoa =  WRITABLE_DIR . PARRAFO_DIR . $txt;
        $this->edukia = WRITABLE_DIR . EDUKI_DIR . $txt;
    }

    # ==========================================================================
    #                       PRIVATE FUNCTIONS: Helpers
    # ==========================================================================

    private function validaciones(){
        $errores = array();

        $requeridos = array("titularra", "parrafoa", "edukia" );
        $errores = validar_requeridos($errores, $requeridos);

        $campoImagen = 'irudia';
        $tipo_permitido = array("image/png", "image/jpeg", "image/gif",
            "image/bmp", "image/jpg");
        $errores= validar_tipoImagen($errores, $tipo_permitido, $campoImagen);

        return $errores;
    }

    private function guardar_parrafoa(){
        $parrafoa_encode = isset($_POST['parrafoa']) ? EuropioCode::encode($_POST['parrafoa']) : '';
        $parrafoa_decode = EuropioCode::decode($parrafoa_encode);

        //TODO poner en $contenido $parrafoa_decode
        $contenido = $_POST['parrafoa'];
        file_put_contents($this->parrafoa, $contenido);
    }

    private function guardar_edukia(){
        $edukia_encode = isset($_POST['edukia']) ? EuropioCode::encode($_POST['edukia']) : '';
        $edukia_decode = EuropioCode::decode($edukia_encode);

        $contenido = "$edukia_decode";
        file_put_contents($this->edukia, $contenido);
    }

}

?>
