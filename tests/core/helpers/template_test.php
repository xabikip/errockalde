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

    function test_render_safe() {
        $base_string = "
        <b>{key1}</b>
        <p>Texto: {key2}</p>
        <!--key3--><i>{key3}</i><!--key3-->
        Texto texto<br>
        <!--key4--><i>{key4}</i><!--key4-->
        Más texto aquí: {key5}, y aquí
        ";

        $expected = "
        <b>Clave Uno</b>
        <p>Texto: Clave Dos</p>
        
        Texto texto<br>
        <i>Clave Cuatro</i>
        Más texto aquí: , y aquí
        ";

        $dict = array(
            "key1"=>"Clave Uno",
            "key2"=>"Clave Dos",
            "key3"=>"",
            "key4"=>"Clave Cuatro",
            "key5"=>""
        );

        $actual = Template($base_string)->render_safe($dict);
        $this->assertEquals($expected, $actual);
    }

}

?>
