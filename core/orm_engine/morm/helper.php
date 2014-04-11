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
import('core.orm_engine.morm.handler');


class MORMHelper {

    public static function set_select_query($obj=NULL) {
        list($compuesto, $x, $tbl) = MORMHandler::analyze($obj);
        unset($x);
        $sql = "SELECT rel_id, compositor, rel FROM $tbl WHERE compuesto = ?";
        return array($sql, $compuesto);
    }
    
    public static function set_insert_query($obj=NULL) {
        list($compuesto, $compositor, $tbl) = MORMHandler::analyze($obj);
        $sql = "INSERT INTO $tbl (compositor, compuesto, rel) 
                VALUES (?, ?, ?)";
        return array($sql, $compuesto, $compositor);
    }
    
    public static function set_delete_query($obj=NULL) {
        list($comp, $x, $tbl) = MORMHandler::analyze($obj);
        unset($x);
        $sql = "DELETE FROM $tbl WHERE compuesto = ?";
        return array($sql, $comp);
    }
}

?>
