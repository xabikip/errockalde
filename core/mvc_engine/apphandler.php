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
    private static $peticiones = array();

    protected static $modulo = '';
    protected static $modelo = '';
    protected static $recurso = '';
    protected static $arg = '';
    protected static $api = False;


    static function handler() {
        self::set_uristr();
        self::set_array();
        self::set_peticiones();
        self::check();
        return array(self::$modulo, 
                     self::$modelo,
                     self::$recurso,
                     self::$arg,
                     self::$api);
    }

    private static function set_uristr() {
        $srvuri = $_SERVER['REQUEST_URI'];
        if(WEB_DIR != "/") {
            self::$uri = str_replace(WEB_DIR, "", $srvuri);
        } else {
            self::$uri = substr($srvuri, 1);
        }
    }

    private static function set_array() {
        self::$peticiones = explode("/", self::$uri);
        if(self::$peticiones[0] == 'api') {
            array_shift(self::$peticiones);
            self::$api = True;
        }
    }

    private static function set_peticiones() {
        if(count(self::$peticiones) == 3) {
            list(self::$modulo, self::$modelo,
                self::$recurso) = self::$peticiones;
        } elseif(count(self::$peticiones) == 4) {
            list(self::$modulo, self::$modelo, self::$recurso,
                self::$arg) = self::$peticiones;
        }
    }

    private static function check() {
        $mu = empty(self::$modulo);
        $mo = empty(self::$modelo);
        $re = empty(self::$recurso);
        if($mu || $mo || $re) HTTPHelper::go(DEFAULT_VIEW);
    }

}

?>
