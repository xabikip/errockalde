<?php

class postController extends Controller {

    public function agregar($errores=array()) {
        $kategoriak_collector = CollectorObject::get('kategoria');
        $kategoriak = $kategoriak_collector->collection;
        $this->view->agregar($kategoriak, $errores);
    }

    public function editar($id=0, $errores=array()) {
        $this->model->post_id = $id;
        $this->model->get();
        $kategoriak_collector = CollectorObject::get('kategoria');
        $kategoriak = $kategoriak_collector->collection;
        $this->view->editar($this->model, $errores, $kategoriak);
    }

    public function guardar() {
        $id = get_data('id');

        $errores = $this->validaciones();

        if($errores) {
            (!$id) ? $this->agregar($errores) : $this->editar($id, $errores);exit();
        }

        $this->model->post_id = $id;

        $this->model->titularra = get_data('titularra');

        (!$id) ? $this->model->sortua = date('Y-m-d') : $this->model->aldatua = date('Y-m-d');

        $kategoria = Pattern::factory('kategoria', get_data('kategoria') );
        $this->model->kategoria = Pattern::composite('kategoria', $kategoria);

        $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
        if($user_id){
            $user = Pattern::factory('User', $user_id );
            $this->model->user = Pattern::composite('User', $user);
        }

        $slugger = new Slugger();
        $this->model->slug =  $slugger->slugify(get_data('titularra'));
        $this->model->save();

        $this->__set_aditional_properties();


        $parrafoa_encode = isset($_POST['parrafoa']) ? EuropioCode::encode($_POST['parrafoa']) : '';
        $edukia_encode = isset($_POST['edukia']) ? EuropioCode::encode($_POST['edukia']) : '';

        if (get_data('parrafoa') !== "") $this->guardar_en_archivo($this->parrafoa,
                                                                   $parrafoa_encode);
        if (get_data('edukia') !== "") $this->guardar_en_archivo($this->edukia,
                                                             $edukia_encode);

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
        $this->model->post_id = (int)$id;
        $this->__set_aditional_properties();
        $this->model->destroy();
        $this->eliminar_archivos();
        HTTPHelper::go("/blog/post/listar");
    }

    public function post($id=0) {
        $collection = CollectorObject::get('kategoria');
        $kategoriak = $collection->collection;

        $post = DataHandler('post')->filter("slug=$id[1]");
        $this->model->post_id = $post[0]['post_id'];
        $this->model->get();

        $this->view->post($this->model, $kategoriak);
    }

    public function posts() {
        $collection = CollectorObject::get('kategoria');
        $kategoriak = $collection->collection;

        $collection = CollectorObject::get('Post');
        $list = $collection->collection;
        $this->view->posts($list, $kategoriak);
    }

    public function posts_kategoria($id=0) {
        $collection = CollectorObject::get('kategoria');
        $kategoriak = $collection->collection;

        $posts = DataHandler('post', DH_FORMAT_OBJECT)->filter("kategoria=$id");
        $this->view->posts($posts, $kategoriak);
    }

    public function get_posts() {
        $collection = CollectorObject::get('Post');
        $list = $collection->collection;
        $this->apidata = $list;
    }

    public function __call($funtzioa, $argumentuak=array()) {
        $id= "/{$this->model->post_id}";
        $this->irudia = WRITABLE_DIR . POST_IRUDI_DIR . $id;
        $this->parrafoa =  WRITABLE_DIR . PARRAFO_DIR . $id;
        $this->edukia = WRITABLE_DIR . EDUKI_DIR . $id;
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

    private function guardar_en_archivo($archivo, $contenido){
        file_put_contents($archivo, $contenido);
    }

    private function eliminar_archivos(){
        file_put_contents($this->irudia, '');
        file_put_contents($this->parrafoa, '');
        file_put_contents($this->edukia, '');
    }

}

?>
