<?php

$actual_path = ini_get('include_path');
require_once "./settings.php";
import('core.orm_engine.objects.standardobject');
import('core.orm_engine.objects.composerobject');
import('core.orm_engine.objects.logicalconnector');
import('tests.core.orm_engine.objects.fakes');
ini_set('include_path', $actual_path);

class ComposerObjectTest extends PHPUnit_Framework_TestCase {

    function setUp() {
        $dbfile = "tests/core/orm_engine/objects/testdb.sql";
        shell_exec("mysql -u" . DB_USER . " -p" . DB_PASS . " < $dbfile");
        $this->compuesto = new Compuesto(Pattern::factory('Foo', 1));
    }
    
    function test_compose_expected_3() {
        $this->compuesto->compuesto_id = 1;
        $this->compuesto->get();
        $expected = $this->compuesto->exclusivo_collection;
        $this->assertEquals(3, count($expected));
    }

    function test_compose_expected_0() {
        $this->compuesto->compuesto_id = 3;
        $this->compuesto->get();
        $expected = $this->compuesto->exclusivo_collection;
        $this->assertEquals(0, count($expected));
    }

    function tearDown() {
        $dbfile = "tests/core/orm_engine/objects/dropdb.sql";
        shell_exec("mysql -u" . DB_USER . " -p" . DB_PASS . " < $dbfile");
    }

}

?>
