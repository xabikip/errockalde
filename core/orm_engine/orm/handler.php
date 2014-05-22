<?php
/**
* Manejador del Mapeador relacional de objetos
*
* Analiza el objeto, obteniendo todos los datos necesarios para crear las
* sentencias SQL
*
* @package    EuropioEngine
* @subpackage ORMEngine
* @license    http://www.gnu.org/licenses/gpl.txt  GNU GPL 3.0
* @author     Eugenia Bahit <ebahit@member.fsf.org>
* @link       http://www.europio.org
*/
class ORMHandler {

    public static function analize($object) {
        $cls = strtolower(get_class($object));
        $parent = self::is_parent($object);
        $tbl = ($parent) ? $parent : $cls;
        $vars = get_object_vars($object);
        $idname = (!$parent) ? "{$cls}_id" : "{$parent}_id";
        $idvalue = $vars[$idname];
        unset($vars[$idname]);

        foreach($vars as $var=>$value) {
            if(is_array($value)) unset($vars[$var]);
        }

        return array($vars, $tbl, $idname);
    }

    # alias
    public static function analyze($obj) { self::analize($obj); }

    public static function get_idname($obj) {
        $cls = strtolower(get_class($obj));
        $parent = self::is_parent($obj);
        $idname = (!$parent) ? "{$cls}_id" : "{$parent}_id";
        return $idname;
    }

    private static function is_parent($obj) {
        $parent = strtolower(get_parent_class($obj));
        $objects = array('standardobject', 'composerobject');
        if(in_array($parent, $objects)) $parent = False;
        return $parent;
    }
}

?>
