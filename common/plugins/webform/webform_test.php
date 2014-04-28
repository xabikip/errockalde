<?php
$actual_path = ini_get('include_path');
require_once "./settings.php";
require_once "./core/helpers/template.php";
ini_set('include_path', $actual_path);


require_once "common/plugins/webform/__init__.php";


class WebFormTest extends PHPUnit_Framework_TestCase {

    function setUp() {
        $this->col = $this->mockear_coleccion();
        $this->coverage = new WebForm();
        $this->coverage->fields = array("", "");
    }

    function test_set_form_expected_ok() {
        $this->assertObjectHasAttribute('action', $this->coverage);
        $this->assertObjectHasAttribute('method', $this->coverage);
        $webform = new WebForm("/action/form");
        $this->assertEquals("/action/form", $webform->action);
        $this->assertEquals("POST", $webform->method);
        $webform = new WebForm();
        $this->assertEquals(".", $webform->action);
        $webform = new WebForm("", "GET");
        $this->assertEquals(".", $webform->action);
        $this->assertEquals("GET", $webform->method);
        $this->assertObjectHasAttribute('fields', $this->coverage);
        $this->assertInternalType('array', $this->coverage->fields);
    }

    function test_add_text_expected_ok() {
        $this->coverage->add_text('nombre');
        $this->assertCount(3, $this->coverage->fields);
        $element = $this->coverage->fields[2];
        $this->assertStringStartsWith("<label ", $element);
        $this->assertRegExp("/(.){1,}name=\"nombre\"(.){1,}/", $element);
        $this->assertRegExp("/(.){1,}id=\"nombre2\"(.){1,}/", $element);
        $this->assertRegExp("/(.){1,}for=\"nombre2\"(.){1,}/", $element);
        $this->assertRegExp("/(.){1,}>Nombre:<\/label>(.){1,}/", $element);
        $this->assertRegExp("/(.){1,}type=\"text\"(.){1,}/", $element);
        $this->assertRegExp("/(.){1,}value=\"\"(.){1,}/", $element);
        $this->assertNotRegExp("/(.){1,}\{extras\}(.){1,}/", $element);
    }

    function test_add_text_with_label_expected_ok() {
        $this->coverage->add_text('nombre', 'Nombre y Apellido');
        $this->assertCount(3, $this->coverage->fields);
        $element = $this->coverage->fields[2];
        $this->assertStringStartsWith("<label ", $element);
        $this->assertRegExp("/(.){1,}>Nombre y Apellido:<\/label>(.){1,}/", 
            $element);
        $this->assertRegExp(
            "/(.){1,}placeholder=\"Nombre y Apellido:\"(.){1,}/", $element);
    }

    function test_add_hidden_expected_ok() {
        $this->coverage->add_hidden('producto', 15);
        $this->assertCount(3, $this->coverage->fields);
        $element = $this->coverage->fields[2];
        $this->assertRegExp("/(.){1,}name=\"producto\"(.){1,}/", $element);
        $this->assertRegExp("/(.){1,}id=\"producto2\"(.){1,}/", $element);
        $this->assertRegExp("/(.){1,}type=\"hidden\"(.){1,}/", $element);
        $this->assertRegExp("/(.){1,}value=\"15\"(.){1,}/", $element);
        $this->assertNotRegExp("/(.){1,}\{extras\}(.){1,}/", $element);
    }

    function test_add_button_expected_ok() {
        $this->coverage->add_submit();
        $this->assertCount(3, $this->coverage->fields);
        $element = $this->coverage->fields[2];
        $this->assertRegExp("/(.){1,}name=\"\"(.){1,}/", $element);
        $this->assertRegExp("/(.){1,}id=\"btSubmit2\"(.){1,}/", $element);
        $this->assertRegExp("/(.){1,}type=\"submit\"(.){1,}/", $element);
        $this->assertRegExp("/(.){1,}value=\"Enviar\"(.){1,}/", $element);
        $this->assertNotRegExp("/(.){1,}\{extras\}(.){1,}/", $element);
    }

    function test_add_file_expected_ok() {
        $this->coverage->add_file('imagen');
        $this->assertCount(3, $this->coverage->fields);
        $element = $this->coverage->fields[2];
        $this->assertRegExp("/(.){1,}name=\"imagen\"(.){1,}/", $element);
        $this->assertRegExp("/(.){1,}id=\"imagen2\"(.){1,}/", $element);
        $this->assertRegExp("/(.){1,}type=\"file\"(.){1,}/", $element);
        $this->assertNotRegExp("/(.){1,}\{extras\}(.){1,}/", $element);
    }

    function test_add_file_with_extras_expected_ok() {
        $this->coverage->add_file('imagen', 'Imagen', 'multiple');
        $this->assertCount(3, $this->coverage->fields);
        $element = $this->coverage->fields[2];
        $this->assertRegExp("/(.){1,}type=\"file\"(.){1,}/", $element);
        $this->assertRegExp("/(.){1,}>Imagen:<\/label>(.){1,}/", $element);
        $this->assertRegExp("/(.){1,} multiple(.){0,}>/", $element);
    }

    function test_prepare_select_expected_ok() {
        $element = $this->coverage->prepare_select('usuario', 'Persona a cargo');
        $this->assertStringStartsWith("<label ", $element);
        $this->assertStringEndsWith("</select><br>\n", $element);
        $this->assertRegExp("/(.){1,}name=\"usuario\"(.){1,}/", $element);
        $this->assertRegExp("/(.){1,}id=\"usuario2\"(.){1,}/", $element);
        $this->assertRegExp("/(.){1,}>Persona a cargo:<\/label>(.|\n){1,}/", 
            $element);
        $this->assertRegExp("/(.|\n){1,}<!--OPTION-->(.|\n){1,}/", $element);
        $actual = Template($element)->get_regex("OPTION");
        $this->assertStringStartsWith("<option ", $actual);
        $this->assertStringEndsWith("</option>", $actual);
    }

    function test_add_select_expected_ok() {
        $this->coverage->add_select('usuario', $this->col, 'Persona a cargo');
        $element = $this->coverage->fields[2];
        $this->assertRegExp("/(.){1,}value=\"2\"(.){1,}/", $element);
        $this->assertRegExp("/(.){1,}value=\"1\"(.){1,}/", $element);
        $this->assertRegExp("/(.){1,}>Valor 1<\/option>(.){1,}/", $element);
        $this->assertRegExp("/(.){1,}>Valor 2<\/option>(.|\n){1,}/", $element);
    }

    function test_add_checkbox() {
        $this->coverage->add_checkbox('usuario', $this->col, 'Personas a cargo');
        $element = $this->coverage->fields[2];
        $this->assertStringStartsWith("<label ", $element);
        $this->assertStringEndsWith("</label><br>\n", $element);
        $this->assertRegExp("/(.|\n){1,}<input (.|\n){1,}/", $element);
        $this->assertRegExp("/(.|\n){1,}type=\"checkbox\"(.|\n){1,}/", $element);
        $this->assertRegExp("/(.|\n){1,}id=\"usuario2\"(.|\n){1,}/", $element);
        $this->assertRegExp("/(.|\n){1,}name=\"usuario\[\]\"(.|\n){1,}/", $element);
    }

    function test_add_radio() {
        $this->coverage->add_radio('usuario', $this->col, 'Personas a cargo');
        $element = $this->coverage->fields[2];
        $this->assertRegExp("/(.|\n){1,}type=\"radio\"(.|\n){1,}/", $element);
        $this->assertRegExp("/(.|\n){1,}name=\"usuario\"(.|\n){1,}/", $element);
    }

    function test_add_textarea() {
        $this->coverage->add_textarea('msg', '', 'Consulta');
        $element = $this->coverage->fields[2];
        $this->assertRegExp("/(.|n){1,}Consulta:<\/label>(.|n){1,}/", $element);
        $this->assertRegExp("/(.){1,}name=\"msg\"(.){1,}/", $element);
        $this->assertRegExp("/(.){1,}id=\"msg2\"(.){1,}/", $element);
        $this->assertRegExp("/(.|\n){1,}><\/textarea><br>$/", $element);
        $this->assertRegExp("/(.){1,}placeholder=\"Consulta:\"(.){1,}/",
            $element);
    }

    function test_add_password() {
        $this->coverage->add_password('clave', 'Contraseña');
        $this->assertCount(3, $this->coverage->fields);
        $element = $this->coverage->fields[2];
        $this->assertStringStartsWith("<label ", $element);
        $this->assertRegExp("/(.){1,}name=\"clave\"(.){1,}/", $element);
        $this->assertRegExp("/(.){1,}id=\"clave2\"(.){1,}/", $element);
        $this->assertRegExp("/(.){1,}for=\"clave2\"(.){1,}/", $element);
        $this->assertRegExp("/(.){1,}>Contraseña:<\/label>(.){1,}/", $element);
        $this->assertRegExp("/(.){1,}type=\"password\"(.){1,}/", $element);
        $this->assertNotRegExp("/(.){1,}\{extras\}(.){1,}/", $element);
    }

    function test_get_input() {
        $str = $this->coverage->form_template;
        $result = Template($str)->get_regex("INPUT");
        $this->assertStringStartsWith("<br><input ", $result);
    }

    function test_get_label() {
        $str = $this->coverage->form_template;
        $result = Template($str)->get_regex("LABEL");
        $this->assertStringStartsWith("<label ", $result);
    }

    function mockear_coleccion() {
        $obj = new Fake();
        return array($obj, clone $obj);
    }

    function test_mockear_coleccion_expected_array() {
        $this->assertInternalType("array", $this->col);
    }

    function test_mockear_coleccion_expected_objects() {

        $i = 1;
        foreach($this->col as $obj) {
            $msg = "\$i = $i; \$obj = " . print_r($obj, True);
            $this->assertEquals($i, $obj->value, $msg);
            $i++;
        }
    }

    function test_show() {
        $this->coverage->add_text('usuario');
        $this->coverage->add_password('clave');
        $this->coverage->add_file('foto');
        $this->coverage->add_hidden('userid', 12);
        $this->coverage->add_select('pais', $this->col);
        $this->coverage->add_checkbox('intereses', $this->col, 'Intereses');
        $this->coverage->add_radio('newsletter', $this->col,
            '¿Deseas suscribirte al Newsletter?');
        $this->coverage->add_textarea('msg');
        $this->coverage->add_submit('Regístrate!');
        $result = $this->coverage->show();
        $this->assertStringStartsWith("\n<form ", $result);
        $this->assertStringEndsWith("</form>\n", $result);
        $this->assertRegExp("/(.|\n){1,}type=\"text\"(.|\n){1,}/", $result);
        $this->assertRegExp("/(.|\n){1,}type=\"password\"(.|\n){1,}/", $result);
        $this->assertRegExp("/(.|\n){1,}type=\"hidden\"(.|\n){1,}/", $result);
        $this->assertRegExp("/(.|\n){1,}type=\"submit\"(.|\n){1,}/", $result);
        $this->assertRegExp("/(.|\n){1,}type=\"checkbox\"(.|\n){1,}/", $result);
        $this->assertRegExp("/(.|\n){1,}type=\"radio\"(.|\n){1,}/", $result);
        $this->assertRegExp("/(.|\n){1,}<option (.|\n){1,}/", $result);
        $this->assertRegExp("/(.|\n){1,}<select (.|\n){1,}/", $result);
        $this->assertRegExp("/(.|\n){1,}<label (.|\n){1,}/", $result);
        $this->assertRegExp("/(.|\n){1,}<textarea (.|\n){1,}/", $result);
    }

    function test_add_link() {
        $href = "/foo/bar/foobar";
        $name = "foobar";
        $anchor = "Foo Bar";
        $this->coverage->add_link($href, $anchor, $name);
        $actual = $this->coverage->fields[2];
        $expected = "<a href=\"$href\" class=\"WebFormLink WebFormLink_$name\">$anchor</a>";
        $this->assertEquals($expected, $actual);
    }
}



class Fake {
    function __construct() {
        $this->value = 1;
        $this->text = "Valor {$this->value}";
    }
    
    function __clone() {
        $this->value++;
        $this->text = "Valor {$this->value}";
    }
}
?>
