<?php
/**
* Vistas del ABM de Usuarios y SessionHandler
*
* @package    EuropioEngine
* @subpackage core.SessionHandler
* @license    http://www.gnu.org/licenses/gpl.txt  GNU GPL 3.0
* @author     Eugenia Bahit <ebahit@member.fsf.org>
* @link       http://www.europio.org
*/


class UserView { 

    public function show_form($user_exists=False, $badpwd=False, $user='',
                              $level=1, $pwd='', $tit='Agregar', $id=0) {

        $dict = $this->__set_dict($user, $pwd, $tit, $id);
        $this->__set_styledict($user_exists, $dict);
        $this->__set_stylepdict($badpwd, $dict);
        $this->__set_optleveldict($level, $dict);

        $str = file_get_contents(
            STATIC_DIR . "html/users/user_form.html");
        $html = Template($str)->render($dict);
        print Template('Agregar Usuario')->show($html);
    }

    public function listar($coleccion=array()) {
        $str = file_get_contents(
            STATIC_DIR . "html/users/user_listar.html");
        foreach($coleccion as &$obj) {
            $obj->admin = ($obj->level == 1) ? "true" : "false";
        }
        $html = Template($str)->render_regex('LISTADO', $coleccion);
        print Template('Usuarios')->show($html);
    }

    public function show_login() {
        $default = STATIC_DIR . "html/login.html";
        $file = (CUSTOM_LOGIN_TEMPLATE) ? CUSTOM_LOGIN_TEMPLATE : $default;
        $tmpl = file_get_contents($file);
        $dict = array("WEB_DIR" => WEB_DIR, "DEFAULT_VIEW"=>DEFAULT_VIEW);
        print Template($tmpl)->render($dict);
    }

    # ==========================================================================
    #                       PRIVATE FUNCTIONS: Helpers
    # ==========================================================================

    private function __set_msgs() {
        $msgu = "El nombre de usuario ya existe o NO es un nombre de";
        $msgu .= " usuario válido (mínimo 6 caracteres; espacios en blanco no";
        $msgu .= " computan.)" . chr(10);
        $msgp = "Contraseña incorrecta. Mínimo 6 caracteres." . chr(10);
        return array($msgu, $msgp);
    }

    private function __set_styledict($user_exists, &$dict) {
        list($msgu, $msgp) = $this->__set_msgs(); unset($msgp);
        if(!$user_exists) {
            $dict['style'] = ''; $dict['error'] = '';
        } else {
            $dict['style'] = ' error';
            $dict['error'] = nl2br($msgu);
        }
    }

    private function __set_stylepdict($badpwd, &$dict) {
        list($msgu, $msgp) = $this->__set_msgs(); unset($msgu);
        if(!$badpwd) {
            $dict['stylep'] = ''; $dict['errorp'] = '';
        } else {
            $dict['stylep'] = ' error';
            $dict['errorp'] = nl2br($msgp);
        }
    }

    private function __set_optleveldict($level, &$dict) {
        if($level > 0 and $level < 6) {
            $dict["opt$level"] = ' selected';
        } else {
            $dict["opt0"] = ' selected';
            $dict['level'] = $level;
            $dict["disabled"] = '';
        }
    }

    private function __set_dict($user, $pwd, $tit, $id) {
        return array('opt1'=>'', 'opt2'=>'', 'opt3'=>'', 'opt4'=>'', 'opt5'=>'',
            'opt0'=>'', 'user'=>$user, 'level'=>'', 'pwd'=>$pwd,
            'disabled'=>'disabled', 'titulo'=>$tit, 'user_id'=>$id);
    }
}

?>
