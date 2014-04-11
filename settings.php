<?php
/**
* Constantes de configuración personalizada.
*
* Este archivo debe renombrarse a settings.php (o ser copiado como tal)
* Al renombrarlo/copiarlo, modificar el valor de todas las constantes.
*
* @package    EuropioEngine
* @license    http://www.gnu.org/licenses/gpl.txt  GNU GPL 3.0
* @author     Eugenia Bahit <ebahit@member.fsf.org>
* @link       http://www.europio.org
*/

session_start();


# ==============================================================================
#                                CONSTANTES
# ==============================================================================
$options = parse_ini_file('config.ini', True);
foreach($options as $section=>$config) {
    if($section != 'PLUGINS') {
        foreach($config as $constant=>$value) {
            define($constant, $value);
        }
    }
}


# ==============================================================================
#                           CONFIGURACIÓN DE PHP
# ==============================================================================
ini_set('include_path', APP_DIR);

if(!PRODUCTION) {
    ini_set('error_reporting', E_ALL | E_NOTICE | E_STRICT);
    ini_set('display_errors', '1');
    ini_set('track_errors', 'On');
} else {
    ini_set('display_errors', '0');
}


# ==============================================================================
#                   HELPER PARA IMPORTACIÓN DE ARCHIVOS
# ==============================================================================
function import($str='', $exit=True) {
    $file = str_replace('.', '/', $str);
    if(file_exists(APP_DIR . "$file.php")) {
        require_once "$file.php";
    } else {
        if($exit) exit("FATAL ERROR: no se pudo importar '$str'");
    }
}

?>
