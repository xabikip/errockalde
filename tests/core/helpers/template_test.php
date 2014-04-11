<?php
$actual_path = ini_get('include_path');
require_once "./settings.php";
require_once "./core/helpers/template.php";
ini_set('include_path', $actual_path);


class TemplateTest extends PHPUnit_Framework_TestCase {

    function setUp() {
        $this->base_string = file_get_contents(
            APP_DIR . "tests/core/helpers/loremipsum");
    }

    function test_get_regex() {
        $result = Template($this->base_string)->get_regex("PARRAFO2");
        $this->assertStringStartsWith("\nDonec malesuada", $result);
        $this->assertStringEndsWith("eget ultricies.\n", $result);
    }

}

?>
