<?php
/**
* Mapeador relacional de objetos multiplicadores
*
* @package    EuropioEngine
* @subpackage ORMEngine
* @license    http://www.gnu.org/licenses/gpl.txt  GNU GPL 3.0
* @author     Eugenia Bahit <ebahit@member.fsf.org>
* @link       http://www.europio.org
*/
import('core.orm_engine.morm.helper');
import('core.orm_engine.mysqlilayer');


class MORM {

    public static function create($obj=NULL) {
        list($sql, $compues, $compo) = MORMHelper::set_insert_query($obj);
        $data = array("iii", "{$obj->compositor->$compo}",
            "{$obj->compuesto->$compues}", "{$obj->rel}");
        $id = MySQLiLayer::ejecutar($sql, $data);
        return $id;
    }

    public static function read($obj=NULL) {
        list($sql, $compuestoid) = MORMHelper::set_select_query($obj);
        $data = array("i", "{$obj->compuesto->$compuestoid}");
        $fields = array("rel_id"=>"", "compositor"=>"", "rel"=>"");
        $results = MySQLiLayer::ejecutar($sql, $data, $fields);
        return $results;
    }
    
    public static function update($obj=NULL) {
    }

    public static function delete($obj=NULL) {
        list($sql, $compid) = MORMHelper::set_delete_query($obj);
        $data = array("i", "{$obj->compuesto->$compid}");
        MySQLiLayer::ejecutar($sql, $data);
    }
}

?>
