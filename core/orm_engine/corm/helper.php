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
import('core.orm_engine.corm.handler');
import('core.orm_engine.orm.handler');


class CORMHelper {

    public static function get_collection($compuesto=NULL, $compositor='') {
        list($a, $b, $c, $d, $property) = CORMHandler::analyze($compuesto,
            $compositor); unset($a, $b, $c, $d);
        return $compuesto->$property;
    }
    
    public static function get_id($compuesto=NULL, $compositor='') {
        list($a, $b, $c, $idname, $d) = CORMHandler::analyze($compuesto,
            $compositor); unset($a, $b, $c, $d);
        return $compuesto->$idname;
    }
    
    public static function set_delete_query($obj=NULL, $compositor='') {
        list($tbl, $d, $a, $b, $c) = CORMHandler::analyze($obj,
            $compositor); unset($a, $b, $c, $d);
        $sql = "DELETE FROM $tbl WHERE compuesto = ?";
        return $sql;
    }
    
    public static function get_addfunc($composer='') {
        return "add_" . strtolower($composer);
    }

    public static function set_insert_dataquery($obj=NULL, $composer='') {
        list($tbl, $compuesto, $compostor, $idname, 
            $property) = CORMHandler::analyze($obj, $composer);
        $sql = "INSERT INTO $tbl (compuesto, compositor)";
        $tmpvar = 0;
        $data = array("");
        foreach($obj->$property as $child) {
            if($tmpvar == 0) {
                $childid = ORMHandler::get_idname($child);
                unset($a, $b);
            }

            $sql .= ($tmpvar > 0) ? ", " : " VALUES ";
            $sql .= "(?, ?)";
            $data[0] .= "ii";
            $data[] = "{$obj->$idname}";
            $data[] = "{$child->$childid}";
            $tmpvar++;
        }
        
        return array($sql, $data);
    }
    
    public static function set_select_query($obj=NULL, $composer='') {
        list($tbl, $a, $b, $c, $d) = CORMHandler::analyze(
            $obj, $composer); unset($a, $b, $c, $d);
        return "SELECT compositor FROM $tbl WHERE compuesto = ?";
    }
}

?>
