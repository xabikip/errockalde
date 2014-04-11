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
import('core.orm_engine.orm.handler');


class CORMHandler {

    public static function analyze($obj, $composername='') {
        $idname = ORMHandler::get_idname($obj);
        $compuesto = strtolower(get_class($obj));
        $compositor = strtolower($composername);
        $tbl = $compositor . $compuesto;
        $property = "{$compositor}_collection";
        
        return array($tbl, $compuesto, $compositor, $idname, $property);
    }

}

?>
