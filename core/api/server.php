<?php
/**
* Clase que provee de una API pública (REST/JSON)
*
* @package    EuropioEngine
* @license    http://www.gnu.org/licenses/gpl.txt  GNU GPL 3.0
* @author     Eugenia Bahit <ebahit@member.fsf.org>
* @link       http://www.europio.org
*/


class ApiRESTFul {

    public static function return_data($data='') {
        if(!headers_sent()) {
            $data = ($data) ? $data : array('data'=>'no data found');
            header("Content-Type: text/json; charset=utf-8");
            print json_encode($data);
        }
    }

}

?>
