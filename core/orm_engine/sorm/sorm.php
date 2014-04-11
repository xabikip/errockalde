<?php
/**
* Mapeador relacional de objetos serializados (freezados)
*
* @package    EuropioEngine
* @subpackage ORMEngine
* @license    http://www.gnu.org/licenses/gpl.txt  GNU GPL 3.0
* @author     Eugenia Bahit <ebahit@member.fsf.org>
* @link       http://www.europio.org
*/
import('core.orm_engine.sorm.helper');
import('core.orm_engine.mysqlilayer');


class SORM {

    public static function create($obj=NULL) {
        $sql = SORMHelper::set_query('INSERT', $obj);
        $sobj = serialize($obj);
        $data = array("s", "$sobj");
        return MySQLiLayer::ejecutar($sql, $data);
    }
    
    public static function read($obj=NULL) {
        $sql = SORMHelper::set_query('SELECT', $obj);
        $data = SORMHelper::get_data($obj, False);
        $fields = SORMHelper::get_fields($obj);
        MySQLiLayer::ejecutar($sql, $data, $fields);
        return $fields;
    }
    
    public static function update($obj=NULL) {
        $sql = SORMHelper::set_query('UPDATE', $obj);
        $data = SORMHelper::get_data($obj);
        MySQLiLayer::ejecutar($sql, $data);
    }
    
    public static function delete($obj=NULL) {
        $sql = SORMHelper::set_query('DELETE', $obj);
        $data = SORMHelper::get_data($obj, False);
        MySQLiLayer::ejecutar($sql, $data);
    }

}

?>
