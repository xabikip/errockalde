<?php

import('core.sessions.helper');
import('core.orm_engine.mysqlilayer');
import('core.helpers.http');


class SessionBaseHandler {

    public function __construct() {
        $this->state = False;
    }

    public function check_user() {
        $user = SessionHelper::get_user();
        $pwd = SessionHelper::get_pwd();
        $data = array("ss", "$user", "$pwd");
        $fields = array("user_id"=>"", "name"=>"", "level"=>"");
        $r = MySQLiLayer::ejecutar(SessionHelper::set_query(), $data, $fields);
        if(count($r) > 0) {
            $this->start_session($fields);
        } else {
            $this->destroy_session();
        }
    }

    public function start_session($data=array()) {
        $_SESSION['login_date'] = time();
        $_SESSION['level'] = $data['level'];
        $_SESSION['user_id'] = $data['user_id'];
        $_SESSION['username'] = $data['name'];
        $this->state = True;
        if(isset($_SESSION['uri'])) HTTPHelper::go($_SESSION['uri']);
        HTTPHelper::go(DEFAULT_VIEW);
    }

    public function destroy_session($login=False) {
        $this->reset_session_vars();
        $this->state = False;
        $u = "/users/user/";
        $npages = array("{$u}check", "{$u}login", "{$u}logout");
        $ruri = $_SERVER['REQUEST_URI'];
        $_SESSION['uri'] = $_SERVER['REQUEST_URI'];
        if(in_array($ruri, $npages)) $_SESSION['uri'] = DEFAULT_VIEW;

        if(!$login) {
            $url = WEB_DIR . "users/user/login";
        } else {
            $url = DEFAULT_VIEW;
        }
        exit(HTTPHelper::go($url));
    }

    public function reset_session_vars() {
        $_SESSION['login_date'] = 0;
        $_SESSION['level'] = 0;
        $_SESSION['user_id'] = 0;
        $_SESSION['username'] = '';
        $_SESSION['uri'] = DEFAULT_VIEW;
    }

    public function check_state($level=1, $uid=0, $strict=SESSION_STRICT_LEVEL) {
        $time = $this->check_time();
        $level = $this->check_level($level, $strict);
        $_uid = ($uid > 0);
        $_level = ($_SESSION['level'] != 1);
        $user = ($_uid && $_level) ? $this->check_userid($uid) : True;
        if($time && (!$level || !$user)) HTTPHelper::exit_by_forbiden();
        if(!$time || !$level || !$user) $this->destroy_session();
    }

    public function check_time() {
        if(!isset($_SESSION['login_date'])) $this->destroy_session();
        $ultimo_acceso = $_SESSION['login_date'];
        $limite_ultimo_acceso = $ultimo_acceso + SESSION_LIFE_TIME;
        if($limite_ultimo_acceso > time()) {
            $_SESSION['login_date'] = time();
            return True;
        }
    }

    public function check_level($level, $strict) {
        if(!isset($_SESSION['level'])) $this->destroy_session();

        $actual = $_SESSION['level'];
        $level_required = ($strict) ? ($actual == $level) : ($actual <= $level);
        $admin = ($actual == 1) ? True : False;
        return ($admin or $level_required) ? True : False;
    }

    public function check_userid($userid) {
        if(!isset($_SESSION['user_id'])) $this->destroy_session();
        return ($_SESSION['user_id'] == $userid) ? True : False;
    }

}


# Compatibilidad para PHP 5.3
function SessionHandler() {
    return new SessionBaseHandler();
}


# Seteo automático de variables de sesión
if(!isset($_SESSION['user_id'])) SessionHandler()->reset_session_vars();
?>
