<?php
import('core.data.datahelper');
import('core.helpers.patterns');


const DH_FILTER_EQ = '=';
const DH_FILTER_NOTEQ = '<>';
const DH_FILTER_LT = '<';
const DH_FILTER_GT = '>';

const DH_FORMAT_DATA = 'data';
const DH_FORMAT_OBJECT = 'object';


class DataHandler {

    public function __construct($table='', $format='') {
        $this->table = $table;
        $this->format = ($format) ? $format : DH_FORMAT_DATA;
    }

    public function get_latest($n=1) {
        $sql = DataHelper::set_query($this->table);
        $data = array("i", "$n");
        list($id, $fields) = DataHelper::explode_scheme($this->table);
        $results = MySQLiLayer::ejecutar($sql, $data, $fields);
        if($this->format == DH_FORMAT_OBJECT) $this->__data2object($results);
        return $results;
    }

    public function filter($condition='', $filter=DH_FILTER_EQ) {
        list($field, $value) = DataHelper::explode_condition(
            $condition, $filter);
        $where = str_replace($value, '?', $condition);
        $sql = DataHelper::set_query($this->table, $where, NULL);
        list($id, $fields) = DataHelper::explode_scheme($this->table);
        $data = array("{$fields[$field]}", "$value");
        foreach($fields as $k=>&$v) $v = "$v";
        $results = MySQLiLayer::ejecutar($sql, $data, $fields);
        if($this->format == DH_FORMAT_OBJECT) $this->__data2object($results);
        return $results;
    }

    private function __data2object(&$data) {
        $cls = ucwords($this->table);
        foreach($data as &$array) {
            $id = "{$this->table}_id";
            $array = Pattern::factory($cls, $array[$id]);
        }
    }

}


function DataHandler($table='', $format=DH_FORMAT_DATA) {
    return new DataHandler($table, $format);
}
?>
