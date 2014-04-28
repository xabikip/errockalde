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

    protected static $modulo = '';
    protected static $modelo = '';
    protected static $recurso = '';
    protected static $arg = '';
    protected static $api = False;
    protected static $pass = False;
    protected static $file = '';
    protected static $controller = '';


    # Inicializa el motor (función principal de toda la app)
    # Si el enrutamiento MVC estándar falla, efectúa una llamada recursiva
    # que acciona el enrutador personalizado
    public static function start($rerouting=False) {
        list(self::$modulo, self::$modelo, self::$recurso, self::$arg,
            self::$api) = ApplicationHandler::handler($rerouting);

        if(self::$api && !API_ENABLED) HTTPHelper::return_api_not_enabled();
        self::check_access();

        if(self::check_file()) {
            require_once self::$file;
            self::check_method();
        }
        if(!self::$pass) self::start(True);
    }

    # Verifica si el acceso se encuentra regsitringido a nivel de la app
    # o a nivel del módulo completo
    private static function check_access() {
        if(defined('RESTRICT_ALL_ACCESS')) {
            if(RESTRICT_ALL_ACCESS && (self::$modelo != 'user')) {
                SessionHandler()->check_state(REQUERID_LEVEL);
            }
        }

        $modulo = self::$modulo;
        if(file_exists(APP_DIR . "appmodules/$modulo/access.php")) {
            require_once APP_DIR . "appmodules/$modulo/access.php";
        }
    }

    # Verifica que exista un archivo controller dentro del módulo
    private static function check_file() {
        $cfile = MVCEngineHelper::set_name('file', self::$modelo);
        $_cfile = MVCEngineHelper::set_name('resource', self::$modelo);
        $modulo = self::$modulo;
        $file = APP_DIR . "appmodules/$modulo/controllers/$cfile.php";
        $_file = APP_DIR . "appmodules/$modulo/controllers/$_cfile.php";
        self::$file = file_exists($file) ? $file : $_file;
        if(file_exists($file) || file_exists($_file)) return True;
    }

    # Verifica que el recurso sea un método accesible del controlador
    private static function check_method() {
        $controllername = MVCEngineHelper::set_name('controller', self::$modelo);
        $resource_name = MVCEngineHelper::set_name('resource', self::$recurso);
        $metodo_existe = method_exists($controllername, $resource_name);
        $metodo_accesible = is_callable(array($controllername, $resource_name));
        self::$controller = $controllername;
        self::$recurso = $resource_name;
        if($metodo_existe && $metodo_accesible) self::call();
    }

    # Llama al controlador solicitado por el usuario
    private static function call() {
        self::$pass = True;
        $controller = new self::$controller(self::$recurso, self::$arg,
            self::$api);
        if(self::$api) ApiRESTFul::return_data($controller->apidata);
    }

}

?>
