<?php

import('appmodules.bazkideak.models.bazkide');
import('appmodules.bazkideak.views.bazkide');

class BazkideController extends Controller {

    public function agregar($errores) {
        $level = 1; # Nivel de acceso mínimo requerido para el recurso
        @SessionHandler()->check_state($level);
        $this->view->agregar($errores);
    }

    public function editar($id=0, $errores=array()) {
        $level = 1; # Nivel de acceso mínimo requerido para el recurso
        @SessionHandler()->check_state($level);
        $this->model->bazkide_id = $id;
        $this->model->get();
        $this->view->editar($this->model, $errores);
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

        $user = $this->userGorde();
        $this->model->user = $user->user_id;
        $this->model->bazkide_id = get_data('id');
        $this->model->izena = get_data('izena');
        $this->model->abizena = get_data('abizena');
        $this->model->goitizena = get_data('goitizena');
        $this->model->emaila = get_data('emaila');
        $this->model->telefonoa = get_data('telefonoa');
        $this->model->save();

        HTTPHelper::go("/bazkideak/bazkide/listar");

    }

    public function listar() {
        $level = 1; # Nivel de acceso mínimo requerido para el recurso
        @SessionHandler()->check_state($level);
        $collection = CollectorObject::get('Bazkide');
        $list = $collection->collection;
        $this->view->listar($list);
    }

    public function eliminar($id=0) {
        $level = 1; # Nivel de acceso mínimo requerido para el recurso
        @SessionHandler()->check_state($level);
        $this->model->bazkide_id = $id;
        $this->model->destroy();
        HTTPHelper::go("/bazkideak/bazkide/listar");
    }

    # ==========================================================================
    #                       PRIVATE FUNCTIONS: Helpers
    # ==========================================================================

    private function validaciones(){
        $errores = array();

        $requeridos = array("izena", "emaila", "erabiltzailea" );
        $errores= validar_requeridos($errores, $requeridos);

        $campoMail = 'emaila';
        $errores = validar_formato_mail($errores, $campoMail);

        return $errores;
    }


    private function userGorde(){
        $user = new User();
        $user->name = $_POST['erabiltzailea'];
        $user->level = 1;
        $user->save(md5($_POST['pasahitza']));
        return $user;
    }
}

?>
