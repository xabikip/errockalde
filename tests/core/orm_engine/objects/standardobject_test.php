<?php

$actual_path = ini_get('include_path');
require_once "./settings.php";
import('core.orm_engine.objects.standardobject');
import('core.orm_engine.objects.logicalconnector');
import('tests.core.orm_engine.objects.fakes');
ini_set('include_path', $actual_path);


class StandardObjectTest extends PHPUnit_Framework_TestCase {

    function setUp() {
        $dbfile = "tests/core/orm_engine/objects/testdb.sql";
        shell_exec("mysql -u" . DB_USER . " -p" . DB_PASS . " < $dbfile");
        $STFake = Pattern::factory('STFake', 1);
        $this->expected_STFake = $STFake;
    }

    function test_get() {
        $actual_STFake = new STFake();
        $actual_STFake->stfake_id = 1;
        $actual_STFake->get();
        $this->assertEquals($this->expected_STFake, $actual_STFake);
        $this->assertCount(3, $actual_STFake->user_collection);
        $this->assertCount(2, $actual_STFake->foo_collection);
        $this->assertInstanceOf('User', $actual_STFake->user_collection[0]);
        $this->assertInstanceOf('User', $actual_STFake->user_collection[1]);
        $this->assertInstanceOf('User', $actual_STFake->user_collection[2]);
        $this->assertInstanceOf('Foo', $actual_STFake->foo_collection[0]);
        $this->assertInstanceOf('Foo', $actual_STFake->foo_collection[1]);
        $this->assertEquals(1, $actual_STFake->user_collection[0]->user_id);
        $this->assertEquals(2, $actual_STFake->user_collection[1]->user_id);
        $this->assertEquals(3, $actual_STFake->user_collection[2]->user_id);
        $this->assertEquals(1, $actual_STFake->foo_collection[0]->foo_id);
        $this->assertEquals(2, $actual_STFake->foo_collection[1]->foo_id);
    }

    function tearDown() {
        $dbfile = "tests/core/orm_engine/objects/dropdb.sql";
        shell_exec("mysql -u" . DB_USER . " -p" . DB_PASS . " < $dbfile");
    }

}

?>
