<?php
/**
* Modelo para el ABM de Usuarios
*
* @package    EuropioEngine
* @subpackage core.SessionHandler
* @license    http://www.gnu.org/licenses/gpl.txt  GNU GPL 3.0
* @author     Eugenia Bahit <ebahit@member.fsf.org>
* @link       http://www.europio.org
*/


class User extends StandardObject {

    public function __construct() {
        $this->user_id = 0;
        $this->name = '';
        $this->level = 0;
    }
    
    function save($pwd=False) {
        if($pwd !== False) {
            $this->pwd = $pwd;
            parent::save();
            unset($this->pwd);
        } else {
            parent::save();
        }       
    }

}

?>
