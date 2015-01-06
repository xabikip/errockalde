<?php

$actual_path = ini_get('include_path');
require_once "./settings.php";
import('core.orm_engine.objects.object');
import('core.orm_engine.objects.standardobject');
import('core.data.datahandler');
import('core.orm_engine.objects.standardobject');
ini_set('include_path', $actual_path);

$use_testing_db = False;

if(!class_exists('User')) {
    eval("class User extends StandardObject {
        function __construct() {
            \$this->user_id = 0;
            \$this->name = '';
            \$this->level = 0;
        }
        public function __clone() {
            \$this->user_id++;
        }
    }
    ");
}


class DataHandlerTest extends PHPUnit_Framework_TestCase {

    function test_get_latest_data() {
        $data = DataHandler('user')->get_latest(1);
        $this->assertGreaterThanOrEqual(1, count($data));
        $this->assertArrayHasKey('level', $data[0]);
    }

    function test_get_latest_object() {
        $data = DataHandler('user', DH_FORMAT_OBJECT)->get_latest(2);
        $this->assertContainsOnlyInstancesOf('User', $data);
    }

    function test_filter() {
        $data = DataHandler('user')->filter("level > 0",
            DH_FILTER_GT);
        $this->assertGreaterThanOrEqual(1, count($data));
    }

    function test_filter_zero_results() {
        $data = DataHandler('user', DH_FORMAT_OBJECT)->filter(
            "user_id=0", DH_FILTER_EQ);
        $this->assertCount(0, $data);
    }

    function test_filter_objects() {
        $data = DataHandler('user', DH_FORMAT_OBJECT)->filter(
            "level=1", DH_FILTER_EQ);
        $this->assertContainsOnlyInstancesOf('User', $data);
    }

}

?>
