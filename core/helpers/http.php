<?php
/**
* Clase que provee de métodos para respuestas HTTP
*
* @package    EuropioEngine
* @subpackage core.helpers
* @license    http://www.gnu.org/licenses/gpl.txt  GNU GPL 3.0
* @author     Eugenia Bahit <ebahit@member.fsf.org>
* @link       http://www.europio.org
*/
class HTTPHelper {

    # Genera respuesta de error 404
    public static function error_response() {
        header("HTTP/1.1 404 Not Found");
        print file_get_contents(APP_DIR . self::get_page(404));
        exit();
    }

    public static function exit_by_forbiden() {
        header('HTTP/1.1 403 Forbidden');
        print file_get_contents(APP_DIR . self::get_page(403));
        exit();
    }

    public static function exit_by_ee1001() {
        print file_get_contents(APP_DIR . self::get_page('EE1001'));
        exit();
    }
 
    public static function return_api_not_enabled() {
        header('HTTP/1.1 403 Forbidden');
        $default_page = "core/helpers/templates/403API.html";
        $html = (HTTP_ERROR_403_API) ? HTTP_ERROR_403_API : $default_page;
        print file_get_contents(APP_DIR . $html);
        exit();
    }

    public static function go($uri='') {
        exit(header("Location: $uri"));
    }

    private static function get_page($num) {
        $constants = array(
            403=>defined("HTTP_ERROR_403") ? HTTP_ERROR_403 : NULL,
            404=>defined("HTTP_ERROR_404") ? HTTP_ERROR_404 : NULL,
            'EE1001'=>defined("HTTP_ERROR_EE1001") ? HTTP_ERROR_EE1001 : NULL,
        );
        $default_page = "core/helpers/templates/$num.html";
        $custom_page = $constants[$num];
        $html = ($custom_page != NULL) ? $custom_page : $default_page;
        return $html;
    }
}

?>
