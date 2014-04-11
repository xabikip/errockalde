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

}

?>
