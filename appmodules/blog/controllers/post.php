<?php

class postController extends Controller {

    # Rekurtsoa erabiltzeko baimen maila
    private static $level = 30;

    public function agregar($errores=array()) {
        @SessionHandler()->check_state(self::$level);
        $kat= CollectorObject::get('kategoria'); $kat = $kat->collection;
        $e = ($errores) ? $errores : array();
        $this->view->agregar($kat, $e);
    }

    public function editar($id=0, $errores=array()) {
        @SessionHandler()->check_state(self::$level);
        $this->model->post_id = $id;
        $this->model->get();
        $kat= CollectorObject::get('kategoria'); $kat = $kat->collection;
        $this->view->editar($this->model, $errores, $kat);
    }

    public function guardar() {
        @SessionHandler()->check_state(self::$level);

        $id = get_data('id');

        $errores = $this->validaciones();

        if(!$errores) {
            $slugger = new Slugger();
            $slug = $slugger->slugify(get_data('titularra'));
            $post = DataHandler('post', DH_FORMAT_OBJECT)->filter("slug=$slug");
            if($post != null){
                if($post[0]->post_id != $id) $errores["titularra"]  = ERROR_MSG_TITLE_DUPLICATE;
            }

        }

        if($errores) {
            (!$id) ? $this->agregar($errores) : $this->editar($id, $errores);
            exit();
        }

        $this->model->post_id = $id;

        $this->model->titularra = get_data('titularra');

        $this->model->slug = $slug;

        if (!$id){
            $this->model->sortua = date('Y-m-d');
        }else {
            $this->model->aldatua = date('Y-m-d');
            $this->model->sortua = get_data("sortua");
        }

        $kategoria = Pattern::factory('kategoria', get_data('kategoria') );
        $this->model->kategoria = Pattern::composite('kategoria', $kategoria);

        $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
        if($user_id){
            $user = Pattern::factory('User', $user_id );
            $this->model->user = Pattern::composite('User', $user);
        }

        $this->model->save();

        # __call fntzio magikoari deia
        $this->__set_aditional_properties();


        $parrafoa_encode = isset($_POST['parrafoa']) ? EuropioCode::encode($_POST['parrafoa']) : '';
        $edukia_encode = isset($_POST['edukia']) ? EuropioCode::encode($_POST['edukia']) : '';

        if (get_data('parrafoa') !== "") $this->guardar_en_archivo($this->parrafoa,
                                                                   $parrafoa_encode);
        if (get_data('edukia') !== "") $this->guardar_en_archivo($this->edukia,
                                                             $edukia_encode);

        $campoImagen = 'irudia';
        guardar_imagen($this->irudia, $campoImagen);

        //indicate which file to resize (can be any type jpg/png/gif/etc...)
        // $file = $this->irudia ;

        //indicate the path and name for the new resized file
        // $resizedFile = WRITABLE_DIR . POST_IRUDI_DIR . "/thumb_home/{$this->model->post_id}";

        //call the function (when passing path to pic)
        // smart_resize_image($file , null, 375 , 255 , true , $resizedFile , false , false ,100 );
        //call the function (when passing pic as string)
        // smart_resize_image(null , file_get_contents($file), SET_YOUR_WIDTH , SET_YOUR_HIGHT , false , $resizedFile , false , false ,100 );


        HTTPHelper::go("/blog/post/listar");
    }

    public function listar() {
        @SessionHandler()->check_state(self::$level);
        $userid = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
        $level = isset($_SESSION['level']) ? $_SESSION['level'] : 0;
        # Admin ez bada, erabiltzailearen postak bakarrik ekarri
        if($level > 1) {
            $list = DataHandler('post', DH_FORMAT_OBJECT)->filter("user=$userid");
        } else {
            $collection = CollectorObject::get('post');
            $list = $collection->collection;
        }
        $this->view->listar($list);
    }

    public function eliminar($id=0) {
        @SessionHandler()->check_state(self::$level);
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

    public function get_ultimos_posts() {
        $ultimos = DataHandler('post', DH_FORMAT_OBJECT)->get_latest(4);
        $this->apidata = $ultimos;
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
        validar_requeridos($errores, $requeridos);

        $campoImagen = 'irudia';
        $mime_permitidos = array(
            "image/png", "image/jpeg", "image/gif", "image/bmp",
            "image/jpg", "image/tiff",
        );
        validar_tipoImagen($errores, $mime_permitidos, $campoImagen);

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
