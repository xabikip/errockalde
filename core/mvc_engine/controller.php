<?php
/**
* ...
*
* @package    EuropioEngine
* @subpackage MVCEngine
* @license    http://www.gnu.org/licenses/gpl.txt  GNU GPL 3.0
* @author     Eugenia Bahit <ebahit@member.fsf.org>
* @link       http://www.europio.org
*/
import('core.mvc_engine.helper');


abstract class Controller {

    public function __construct($resource='', $arg='', $api=False) {
        $this->api = $api;
        $this->apidata = '';
        $this->model = MVCEngineHelper::get_model($this);
        $this->view = MVCEngineHelper::get_view($this);
        $metodo_existe = method_exists($this, $resource);
        $metodo_accesible = is_callable(array($this, $resource));
        if($metodo_existe && $metodo_accesible) {
            call_user_func(array($this, $resource), $arg);
        } else {
            HTTPHelper::error_response();
        }
    }
}

?>
