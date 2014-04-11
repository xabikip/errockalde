<?php
/**
* Ayudante del Mapeador Relacional de Objetos
*
* @package    EuropioEngine
* @subpackage ORMEngine
* @license    http://www.gnu.org/licenses/gpl.txt  GNU GPL 3.0
* @author     Eugenia Bahit <ebahit@member.fsf.org>
* @link       http://www.europio.org
*/
import('core.orm_engine.orm.handler');


class ORMHelper {

    # Construir sentencias INSERT
    static function set_insert_query($object) {
        list($vars, $tbl, $idname) = ORMHandler::analize($object);
        $insert_fields = implode(", ", array_keys($vars));
        $insert_values = implode(", ", array_fill(0, count($vars), "?"));
        return "INSERT INTO $tbl ($insert_fields) VALUES ($insert_values)";
    }

    # Construir sentencias UPDATE
    static function set_update_query($object) {
        list($vars, $tbl, $idname) = ORMHandler::analize($object);
        $update_fields = implode(" = ?, ", array_keys($vars));
        return "UPDATE $tbl SET $update_fields = ? WHERE $idname = ?";
    }

    # Construir sentencias DELETE
    static function set_delete_query($object) {
        list($vars, $tbl, $idname) = ORMHandler::analize($object);
        return "DELETE FROM $tbl WHERE $idname = ?";
    }

    # Construir sentencias SELECT
    static function set_select_query($object) {
        list($vars, $tbl, $idname) = ORMHandler::analize($object);
        $insert_fields = implode(", ", array_keys($vars));
        return "SELECT $idname, $insert_fields FROM $tbl WHERE $idname = ?";
    }

    # Setear el array $data
    static function get_data($object, $iscreate=False) {
        $data = array('');
        list($vars, $tbl, $idname) = ORMHandler::analize($object);
        foreach($vars as $var) {
            $type = gettype($var);
            if($type == 'object' or $type == 'boolean') $type = 'integer';
            $data[0] .= $type[0];
            $value = self::get_value($var);
            $data[] = "$value";
        }
        if(!$iscreate) {
            $data[0] .= "i";
            $data[] = "{$object->$idname}";
        }
        return $data;
    }

    # Setear valores de tratamiento especial para el array $data
    private static function get_value($var='') {
        if(gettype($var) == 'object') {
            list($vars, $tbl, $idname) = ORMHandler::analize($var);
            $var = $var->$idname;
        }
        if($var === False) $var = 0;
        return $var;
    }


}

?>
