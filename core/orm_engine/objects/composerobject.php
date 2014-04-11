<?php
import('core.orm_engine.objects.standardobject');


abstract class ComposerObject extends StandardObject {

    function compose($obj) {
        $idname_other = ORMHandler::get_idname($obj);
        $idname_this = ORMHandler::get_idname($this);
        $fieldname = str_replace('_id', '', $idname_other);
        $table = str_replace('_id', '', $idname_this);
        $sql = "SELECT $idname_this FROM $table WHERE $fieldname = ?";
        $data = array("i", "{$obj->$idname_other}");
        $fields = array("id"=>"");
        $results = MySQLiLayer::ejecutar($sql, $data, $fields);
        foreach($results as $array_fields) {
            $id = $array_fields['id'];
            $funcname = "add_$table";
            $obj->$funcname(Pattern::factory(ucwords($table), $id));
        }
    }

}

?>
