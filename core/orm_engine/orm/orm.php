<?php
/**
* Mapeador relacional de objetos de objetos
*
* Provee de métodos CRUD para cualquier objeto estándar
*
* @package    EuropioEngine
* @subpackage ORMEngine
* @license    http://www.gnu.org/licenses/gpl.txt  GNU GPL 3.0
* @author     Eugenia Bahit <ebahit@member.fsf.org>
* @link       http://www.europio.org
*/
import('core.orm_engine.orm.helper');
import('core.orm_engine.orm.handler');
import('core.orm_engine.mysqlilayer');


class ORM {

    # Guardar datos
    public static function create($object=NULL) {
        $sql = ORMHelper::set_insert_query($object);
        $data = ORMHelper::get_data($object, True);
        return MySQLiLayer::ejecutar($sql, $data);
    }

    # Leer datos
    public static function read($object=NULL) {
        list($vars, $tbl, $idname) = ORMHandler::analize($object);
        $sql = ORMHelper::set_select_query($object);
        $data = array("i", "{$object->$idname}");
        $fields = array("$idname" => "");
        foreach($vars as $key=>$value) $fields["$key"] = "";
        MySQLiLayer::ejecutar($sql, $data, $fields);
        return $fields;
    }

    # Actualizar datos
    public static function update($object=NULL) {
        $sql = ORMHelper::set_update_query($object);
        $data = ORMHelper::get_data($object);
        MySQLiLayer::ejecutar($sql, $data);
    }

    # Eliminar datos
    public static function delete($object=NULL) {
        list($vars, $tbl, $idname) = ORMHandler::analize($object);
        $sql = ORMHelper::set_delete_query($object);
        $data = array("i", "{$object->$idname}");
        MySQLiLayer::ejecutar($sql, $data);
    }
}

?>
