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
import('core.helpers.http');


class ApplicationHandler {

    private static $uri = '';
    private static $_REQUEST_URI = NULL;
    private static $peticiones = array();

    protected static $modulo = '';
    protected static $modelo = '';
    protected static $recurso = '';
    protected static $arg = '';
    protected static $api = False;


    # Manejador principal de peticiones del usuario
    static function handler($rerouting=False) {
        self::clean_uri();
        self::explode_uri();
        self::analyze_uri();
        if($rerouting) self::rerouting();
        return array(self::$modulo, 
                     self::$modelo,
                     self::$recurso,
                     self::$arg,
                     self::$api);
    }

    # Limpia la URI para que se puedan extraer las solicitudes del usuario
    private static function clean_uri() {
        $real_uri = $_SERVER['REQUEST_URI'];
        $virtual_uri = self::$_REQUEST_URI;
        $srvuri = (!is_null($virtual_uri)) ? $virtual_uri : $real_uri;
        if(WEB_DIR != "/") {
            self::$uri = str_replace(WEB_DIR, "", $srvuri);
        } else {
            self::$uri = substr($srvuri, 1);
        }
    }

    # Extrae las solicitudes de la URI
    private static function explode_uri() {
        self::$peticiones = explode("/", self::$uri);
        if(self::$peticiones[0] == 'api') {
            array_shift(self::$peticiones);
            self::$api = True;
        }
    }

    # Analiza las solicitudes extraídas de la URI
    private static function analyze_uri($i=0) {
        $properties = array('modulo', 'modelo', 'recurso', 'arg');
        foreach(self::$peticiones as $param) {
            if(isset($properties[$i])) {
                $name = $properties[$i];
                self::$$name = $param;
            } else {
                settype(self::$arg, 'array');
                self::$arg = array_merge(self::$arg, array($param));
            }
            $i++;
        }
    }

    # Enrutador personalizado
    # Solo se acciona cuando el enrutamiento MVC estándar falla
    # Si no se encuentra definida una URL personalizada que coincida con la URI
    # solicitada por el usuario, se redirige a la vista por defecto
    public static function rerouting() {
        $pass = False;
        $urls_file = APP_DIR . "urls.php";
        if(file_exists($urls_file)) {
            eval(file_get_contents($urls_file));
            $resource = $_SERVER['REQUEST_URI'];
            foreach($urls as $regex=>$to_url) {
                preg_match($regex, $resource, $matches);
                if(isset($matches[0])) {
                    self::$_REQUEST_URI = "$to_url{$resource}";
                    $pass = True;
                    self::handler();
                    break;
                }
            }
        }
        if(!$pass) HTTPHelper::go(DEFAULT_VIEW);
    }

}

?>
