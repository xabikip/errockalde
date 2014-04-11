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


class MORMHandler {

    public static function analyze($obj) {
        $idncompuesto = ORMHandler::get_idname($obj->compuesto);
        $idncompositor = ORMHandler::get_idname($obj->compositor);

        $tbl = strtolower(get_class($obj->compositor) . 
            get_class($obj->compuesto));

        return array($idncompuesto, $idncompositor, $tbl);

    }
}

?>
