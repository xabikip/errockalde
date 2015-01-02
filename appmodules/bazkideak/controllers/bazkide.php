<?php

import('appmodules.bazkideak.models.bazkide');
import('appmodules.bazkideak.views.bazkide');

class BazkideController extends Controller {

    public function agregar($errores) {
        $level = 1; # Nivel de acceso mínimo requerido para el recurso
        @SessionHandler()->check_state($level);
        $e = ($errores) ? $errores : array();
        $this->view->agregar($e);
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

    public function berreskuratu() {
        $errores = false;
        $ok = false;
        $this->view->berreskuratu($errores, $ok);
    }

    public function tokenBidali() {
        $ok = false;

        $errores = array();
        $requeridos = array("mail");
        validar_requeridos($errores, $requeridos);
        if($errores) {
            $this->view->berreskuratu($errores, $ok); exit();
        }
        $email =  get_data('mail');

        $result = DataHandler('bazkide')->filter("emaila=$email");
        $errores = (count($result) > 0) ? false : true;


        if(!$errores){
            $ok = true;

            $m = $email . Time();
            $valor_hash = hash('sha512', $m);

            eval("class pasahitzberria extends StandardObject { }");
            $rc = new pasahitzberria();
            $rc->pasahitzberria_id = 0;
            $rc->user = $result[0]['user'];
            $rc->token = $valor_hash;
            $rc->data = date("Y-m-d H:i:s");
            $rc->passberria = md5(EuropioCode::reverse($_POST['passberria']));
            $rc->save();

            $html = file_get_contents(CUSTOM_STATIC_DIR . "/html/back/bazkideak/pasahitzaMail.html");
            $msg = str_replace("{token}", $valor_hash, $html);

            if(PRODUCTION) {
                $mail_to = $email;
                $mail_head  = "MIME-Version: 1.0\r\n";
                $mail_head .= "Content-type: text/html; charset=utf-8\r\n";
                $mail_head .= "To: {$mail_to}\r\n";
                $mail_head .= "From: Astinddu Musika<astinddu@gmail.com>\r\n";
                $mail_head .= "Reply-To: Astinddu Musika<astinddu@gmail.com>\r\n";
                mail($mail_to, "Astindduko pasahitza berreskuratu", $msg, $mail_head);
                $ok = true;
                $this->view->berreskuratu($errores, $ok);
            }else{
                $this->view->berreskuratu($errores, $ok);
            }
        }else{
            $this->view->berreskuratu($errores, $ok); exit();
        }
    }

    public function pasahitzBerria($token){
        $result = DataHandler('pasahitzberria')->filter("token=$token");

        $u = new User();
        $u->user_id = $result[0]['user'];
        $u->get();
        $u->save($result[0]['passberria']);

        eval("class pasahitzberria extends StandardObject { }");
        $rc = new pasahitzberria();
        $rc->pasahitzberria_id = $result[0]['pasahitzberria_id'];
        $rc->destroy();

        $this->view->ongiberreskuratu();

    }


    # ==========================================================================
    #                       PRIVATE FUNCTIONS: Helpers
    # ==========================================================================

    private function validaciones(){
        $errores = array();

        $requeridos = array("izena", "emaila", "erabiltzailea" );
        validar_requeridos($errores, $requeridos);

        $campoMail = 'emaila';
        validar_formato_mail($errores, $campoMail);

        return $errores;
    }


    private function userGorde(){
        $user = new User();
        $user->name = $_POST['erabiltzailea'];
        $user->level = 30;
        $user->save(md5(EuropioCode::reverse($_POST['pasahitza'])));
        return $user;
    }
}

?>
