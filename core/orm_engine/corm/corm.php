<?php
/**
* Mapeador relacional de objetos de conexión lógica
*
* @package    EuropioEngine
* @subpackage ORMEngine
* @license    http://www.gnu.org/licenses/gpl.txt  GNU GPL 3.0
* @author     Eugenia Bahit <ebahit@member.fsf.org>
* @link       http://www.europio.org
*/
import('core.orm_engine.mysqlilayer');
import('core.orm_engine.corm.helper');


class CORM {

    public static function delete($obj=NULL, $composer='') {
        $sql = CORMHelper::set_delete_query($obj, $composer);
        $id = CORMHelper::get_id($obj, $composer);
        $data = array("i", "$id");
        MySQLiLayer::ejecutar($sql, $data);
    }

    public static function create($obj=NULL, $composer='') {
        list($sql, $data) = CORMHelper::set_insert_dataquery($obj, $composer);
        return MySQLiLayer::ejecutar($sql, $data);
    }
    
    public static function read($obj=NULL, $composer='') {
        $sql = CORMHelper::set_select_query($obj, $composer);
        $id = CORMHelper::get_id($obj, $composer);
        $data = array("i", "$id");
        $fields = array("id"=>"");
        return MySQLiLayer::ejecutar($sql, $data, $fields);
    }
}

?>
