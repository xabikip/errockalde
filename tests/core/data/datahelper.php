<?php

$actual_path = ini_get('include_path');
require_once "./settings.php";
import('core.orm_engine.objects.object');
import('core.orm_engine.objects.standardobject');
import('core.data.datahelper');
import('core.data.datahandler');
ini_set('include_path', $actual_path);

$use_testing_db = False;


class DataHelperTest extends PHPUnit_Framework_TestCase {

    function setUp() {
        $this->expected_scheme = array(
            array('column'=>'user_id', 'type'=>'i'),
            array('column'=>'name', 'type'=>'s'),
            array('column'=>'level', 'type'=>'i'),
            array('column'=>'pwd', 'type'=>'s')
        );
    }

    function test_get_scheme() {
        $actual_scheme = DataHelper::get_scheme('user');
        $this->assertEquals($this->expected_scheme, $actual_scheme);
    }

    function test_explode() {
        $expected_id = "user_id";
        $expected_fields = array(
            "user_id"=>"i", "name"=>"s", "level"=>"i", "pwd"=>"s"
        );
        list($actual_id, $actual_fields) = DataHelper::explode_scheme('user');
        $this->assertEquals($expected_id, $actual_id);
        $this->assertEquals($expected_fields, $actual_fields);
    }

    function test_query_latest() {
        $sql = "SELECT user_id, name, level, pwd ";
        $sql .= "FROM user ORDER BY user_id DESC LIMIT ?";
        $actual = DataHelper::set_query('user');
        $this->assertEquals($sql, $actual);
    }

    function test_query_filter() {
        $sql = "SELECT user_id, name, level, pwd ";
        $sql .= "FROM user WHERE level = ? ";
        $actual = DataHelper::set_query('user', 'level = ?', NULL);
        $this->assertEquals($sql, $actual);
    }

    function test_query_filter_and_latest() {
        $sql = "SELECT user_id, name, level, pwd ";
        $sql .= "FROM user WHERE level = ? ORDER BY user_id DESC LIMIT ?";
        $actual = DataHelper::set_query('user', 'level = ?');
        $this->assertEquals($sql, $actual);
    }

    function test_explode_condition() {
        list($field, $value) = DataHelper::explode_condition("level = 1",
            DH_FILTER_EQ);
        $this->assertEquals("level", $field);
        $this->assertEquals("1", $value);
    }
}

?>
