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
import('core.mvc_engine.apphandler');
import('core.mvc_engine.helper');
import('core.api.server');
import('core.helpers.http');


class FrontController {

    public static function start() {
        list($modulo, $modelo, $recurso, $arg, $api) = ApplicationHandler::handler();

        if($api && !API_ENABLED) HTTPHelper::return_api_not_enabled();
        
        self::check_access();
        $cfile = MVCEngineHelper::set_name('file', $modelo);
        $_cfile = MVCEngineHelper::set_name('resource', $modelo);
        $controllername = MVCEngineHelper::set_name('controller', $modelo);
        $resource_name = MVCEngineHelper::set_name('resource', $recurso);
        $file = APP_DIR . "appmodules/$modulo/controllers/$cfile.php";
        $_file = APP_DIR . "appmodules/$modulo/controllers/$_cfile.php";

        if(file_exists($file) || file_exists($_file)) {
            $file = file_exists($file) ? $file : $_file;
            require_once "$file";
            $controller = new $controllername($resource_name, $arg, $api);
            if($api) ApiRESTFul::return_data($controller->apidata);
        } else {
            HTTPHelper::go(DEFAULT_VIEW);
        }
    }

    public static function check_access() {
        list($modulo, $modelo, $recurso, $arg,
            $api) = ApplicationHandler::handler();

        if(defined('RESTRICT_ALL_ACCESS')) {
            if(RESTRICT_ALL_ACCESS && ($modelo != 'user')) {
                SessionHandler()->check_state(REQUERID_LEVEL);
            }
        }
        
        if(file_exists(APP_DIR . "appmodules/$modulo/access.php")) {
            require_once APP_DIR . "appmodules/$modulo/access.php";
        }
    }
}

?>
