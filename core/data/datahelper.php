<?php
import('core.orm_engine.mysqlilayer');


class DataHelper {

    public static function get_scheme($table) {
        $sql = "SELECT  COLUMN_NAME,
                        IF(COLUMN_TYPE LIKE '%int%', 'i',
                            IF(COLUMN_TYPE LIKE '%decimal%', 'd', 's')
                        )
                FROM    COLUMNS
                WHERE   TABLE_SCHEMA = ?
                AND     TABLE_NAME = ?
        ";
        $data = array('ss', DB_NAME, "$table");
        $fields = array('column'=>'', 'type'=>'');
        return MySQLiLayer::ejecutar($sql, $data, $fields,
            'INFORMATION_SCHEMA');
    }

    public static function explode_scheme($table) {
        $scheme = self::get_scheme($table);
        $campos = array();
        $id = "";
        foreach($scheme as $array) {
            if(count($campos) == 0) $id = $array['column'];
            $campos[$array['column']] = "{$array['type']}";
        }
        return array($id, $campos);
    }

    public static function set_query($table, $where=NULL, $type='LATEST') {
        list($id, $campos) = self::explode_scheme($table);
        $str_campos = implode(', ', array_keys($campos));
        $sql = "SELECT $str_campos FROM $table ";
        if(!is_null($where)) $sql .= "WHERE $where ";
        if($type == 'LATEST') $sql .= "ORDER BY $id DESC LIMIT ?";
        return $sql;
    }

    public static function explode_condition($condition, $filter) {
        list($field, $value) = explode($filter, $condition);
        return array(trim($field), trim($value));
    }
}

?>
