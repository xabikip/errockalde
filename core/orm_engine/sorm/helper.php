<?php
/**
* ...
*
* @package    EuropioEngine
* @subpackage ORMEngine
* @license    http://www.gnu.org/licenses/gpl.txt  GNU GPL 3.0
* @author     Eugenia Bahit <ebahit@member.fsf.org>
* @link       http://www.europio.org
*/


class SORMHelper {

    public static function get_names($obj=NULL) {
        $tbl = strtolower(get_class($obj));
        $idname = "{$tbl}_id";
        return array($tbl, $idname);
    }

    # Establecer queries para objetos persistentes
    public static function set_query($type='', $obj=NULL) {
        list($tbl, $idname) = self::get_names($obj);
        switch($type) {
            case 'INSERT':
                return "INSERT INTO $tbl ($tbl) VALUES (?)";
                break;
            case 'UPDATE':
                return "UPDATE $tbl SET $tbl = ? WHERE $idname = ?";
                break;
            case 'SELECT':
                return "SELECT $idname, $tbl FROM $tbl WHERE $idname = ?";
                break;
            case 'DELETE':
                return "DELETE FROM $tbl WHERE $idname = ?";
                break;
        }
    }

    public static function get_data($obj, $serial=True) {
        list($tbl, $idname) = self::get_names($obj);
        $data = array("i", "{$obj->$idname}");

        if($serial) {
            $sobj = serialize($obj);
            $data = array("si", "$sobj", "{$obj->$idname}");
        }
        
        return $data;
    }
    
    public static function get_fields($obj=NULL) {
        list($tbl, $idname) = self::get_names($obj);
        return array("$idname"=>"", "$tbl"=>"");
    }
        
}

?>
