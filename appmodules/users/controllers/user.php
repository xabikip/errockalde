<?php
/**
* Controlador para el ABM de Usuarios y el SessionHandler
*
* @package    EuropioEngine
* @subpackage core.SessionHandler
* @license    http://www.gnu.org/licenses/gpl.txt  GNU GPL 3.0
* @author     Eugenia Bahit <ebahit@member.fsf.org>
* @link       http://www.europio.org
*/


class UserController extends Controller {

    # ==========================================================================
    #                    Recursos básicos (estándar)
    # ==========================================================================

    public function agregar() {
        @SessionHandler()->check_state(1);
        $this->view->show_form();
    }

    public function editar($id=0) {
        @SessionHandler()->check_state(1);
        $this->model->user_id = $id;
        $this->model->get();
        $user = $this->model->name;
        $level = $this->model->level;
        $this->view->show_form(False, False, $user, $level, '', 'Editar', $id);
    }

    public function guardar() {
        @SessionHandler()->check_state(1);
        $badpwd = False;
        $id = (isset($_POST['id'])) ? $_POST['id'] : 0;
        $tit = ($id > 0) ? 'Editar' : 'Agregar';
        $this->model->user_id = $id;

        $user_exists = $this->__validar_user($id);
        $this->__set_level();
        list($pwd, $badpwd) = $this->__validar_pwd($id);

        if(!$user_exists and !$badpwd) {
            $this->model->save($pwd);
            HTTPHelper::go("/users/user/listar");
        } else {
            $this->view->show_form($user_exists, $badpwd, $this->model->name, 
                $this->model->level, $_POST['pwd'], $tit, $id);
        }
    }

    public function listar() {
        @SessionHandler()->check_state(1);
        $collection = CollectorObject::get('User');
        $list = $collection->collection;
        $this->view->listar($list);
    }

    public function eliminar($id=0) {
        @SessionHandler()->check_state(1);
        $this->model->user_id = $id;
        $this->model->destroy();
        HTTPHelper::go("/users/user/listar");
    }

    # ==========================================================================
    #                    Recursos manejados x SessionHandler
    # ==========================================================================

    # /users/user/login (formulario para loguearse)
    public function login() {
        $this->view->show_login();
    }

    # /users/user/check (valida los datos ingresados en el form de login)
    public function check() {
        SessionHandler()->check_user();
        HTTPHelper::go(DEFAULT_VIEW);
    }

    # /users/user/logout (desconexión)
    public function logout() {
        SessionHandler()->destroy_session(True);
    }

    # ==========================================================================
    #                       PRIVATE FUNCTIONS: Helpers
    # ==========================================================================

    private function __validar_user($id=0) {
        $user_exists = False;
        $user = str_replace(' ', '', SessionHelper::get_user());
        if(strlen($user) < 6) $user_exists = True;
        $this->model->name = $user;

        $collection = CollectorObject::get('User');
        $list = $collection->collection;
        foreach($list as $obj) {
            $mismo_nombre = ($obj->name == $this->model->name);
            $distinta_id = ($obj->user_id != $id);
            if($mismo_nombre && $distinta_id) $user_exists = True;
        }

        return $user_exists;
    }

    private function __set_level() {
        $level = (isset($_POST['otro'])) ? $_POST['otro'] : $_POST['level'];
        $this->model->level = (int)$level;
    }

    private function __validar_pwd($id=0) {
        $badpwd = false;
        $pwd_ = $_POST['pwd'];
        if(strlen($pwd_) < 6) $badpwd = True;
        $pwd = (!$badpwd) ? str_replace(' ', '', 
            SessionHelper::get_pwd()) : False;
        if($pwd_ == '' && $id > 0) $badpwd = False;
        return array($pwd, $badpwd);
    }

}

?>
