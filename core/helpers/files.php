<?php
/**
* Clase que provee de métodos para manejar archivos
*
* @package    EuropioEngine
* @subpackage core.helpers
* @license    http://www.gnu.org/licenses/gpl.txt  GNU GPL 3.0
* @author     Eugenia Bahit <ebahit@member.fsf.org>
* @link       http://www.europio.org
*/
class FileManager {

    public static function show($file='', $download=False, $name='') {
        if(file_exists($file)) {
            $resource = finfo_open(FILEINFO_MIME_TYPE);
            $type = finfo_file($resource, $file);
            finfo_close($resource);
            header("Content-type: $type");
            if($download) {
                $partes = explode('.', $file);
                $total = count($partes);
                $ext = ($total > 0) ? $partes[$total - 1] : 'txt';
                if(!$name) $name = "download.$ext";
                header("Content-Disposition: attachment; filename=$name;");
            }
            readfile($file);
        } else {
            HTTPHelper::error_response();
        }
    }

    public static function get_clean_uri() {
        $clean_uri = str_replace($_SERVER['QUERY_STRING'], '',
            $_SERVER['REQUEST_URI']);
        $clean_uri = str_replace('?', '', $clean_uri);
        $clean_uri = strip_tags(urldecode($clean_uri));
        $clean_uri = str_replace('../', '', $clean_uri);
        $file = str_replace('/uploadfiles/', '', $clean_uri);
        $file = filter_var($file, FILTER_SANITIZE_URL);
        return $file;
    }

}


/*
    Servidor de archivos estáticos
    
    Permite servir archivos estáticos como imágenes, PDF, documentos de 
    Libre/Open Office así como cualquier otro archivo que no requiera ser
    interpretado por el servidor, pero que no se encuentre en un directorio
    accesible vía Web.
    
    Se inicializa de forma automática y especialmente útil para los archivos
    cargados desde WebForms y almacenados en el directorio con permisos de 
    escritura no servido, configurado en la constante WRITABLE_DIR.
    
    Usage:
        Ejemplo para imágenes en archivo .html

            <img src="/uploadfiles/imagen.png">

        /uploadfiles/ es la ruta virtual predeterminada para referirse a
                      archivos estáticos no servidos.
        imagen.png    es la ruta del archivo dentro de WRITABLE_DIR

    Require configurar .htacces con las siguientes reglas:
    RewriteRule !(^static|^uploadfiles|^core/helpers/files.php$|favicon) app_engine.php
    RewriteRule ^uploadfiles core/helpers/files.php

*/
class StaticFileServer {

    private static $instance;

    private function __construct() {
        $path = str_replace('/core/helpers/files.php', '', __FILE__);
        include "$path/settings.php";
    }

    private function serve_file() {
        $file = FileManager::get_clean_uri();
        $file_path = WRITABLE_DIR . $file;
        if(file_exists($file_path)) FileManager::show($file_path);
    }

    public static function run() {
        if($_SERVER['SCRIPT_FILENAME'] == __FILE__) {
            if(!isset(self::$instance)) {
                self::$instance = new StaticFileServer();
            }
            self::$instance->serve_file();
        }
    }
}


StaticFileServer::run();

?>
