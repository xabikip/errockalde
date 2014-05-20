<?php
$actual_path = ini_get('include_path');
require_once "./settings.php";
require_once "./core/helpers/template.php";
ini_set('include_path', $actual_path);


require_once "common/plugins/securitylayer/__init__.php";


class SafeTest extends PHPUnit_Framework_TestCase {

    function setUp() {
        $_POST['codestring'] = "ABC <script>bcd; <b>bb";
        $_POST['codestring2strict'] = "ABC <script>bcd;</script> <b>bb";
        $_POST['email'] = "eugenia@mimail.net";
        $_POST['mail'] = "euge,nia<b>@mi'mail.net";
        $_POST['float'] = "1278.45";
        $_POST['floatthousand'] = "7,461.98";
        $_POST['floatcolon'] = "989,33";
        $_POST['integer'] = 123;
        $_POST['opciones'] = array(15, 76, 191, 218);
        $_POST['opciones_con_error'] = array('5manzanas', 'string', '28');
        $_POST['nonascii'] = 'Ñandú';
        $_POST['nonascii2'] = "Ñ'an<b>d\"ú</b>";
        $_POST['telefono'] = "54365487698062";
        $_POST['password'] = "Ja:C1n;T>0";
        $this->original = $_POST;
    }

    # String
    function test_encode_string_with_cadena() {
        SecurityLayer(False)->clean_post_data();
        $expected = "ABC &#60;script&#62;bcd; &#60;b&#62;bb";
        $this->assertEquals($expected, $_POST['codestring']);
        $this->assertEquals($this->original['email'], $_POST['email']);
        $this->assertEquals($this->original['integer'], $_POST['integer']);
    }
    
    function test_encode_string_nonascii() {
        SecurityLayer(False)->clean_post_data();
        $expected = "Ñ&#39;an&#60;b&#62;d&#34;ú&#60;/b&#62;";
        $this->assertEquals($expected, $_POST['nonascii2']);
    }

    #password
    function test_hashing_password() {
        $expected = "a87a4cfaca3d80a56c8992fdfcb5e8a8"; # MD5 de Ja:C1n;T>0
        SecurityLayer(False)->clean_post_data();
        $this->assertEquals($expected, $_POST['password']);
    }

    # E-mail
    function test_purge_email_with_mail() {
        SecurityLayer(False)->clean_post_data();
        $expected = "eugeniab@mi&#39;mail.net";
        $this->assertEquals($expected, $_POST['mail']);
    }

    # Intiger and Float
    function test_sanitize_number() {
        SecurityLayer(False)->clean_post_data();
        $this->assertEquals(7461.98, $_POST['floatthousand']);
        $this->assertEquals(1278.45, $_POST['float']);
        $this->assertEquals(989.33, $_POST['floatcolon']);
        $this->assertEquals(123, $_POST['integer']);
        $this->assertEquals(54365487698062, $_POST['telefono']);
    }
    
    # String strip & convert
    function test_strict() {
        SecurityLayer(True)->clean_post_data();
        $expected = "ABC bcd; bb";
        $this->assertEquals($expected, $_POST['codestring2strict']);
    }
    
    # Sanitize array
    function test_saniteze_number_in_array() {
        SecurityLayer(False)->clean_post_data();
        $expected = array(15, 76, 191, 218);
        $this->assertEquals($expected, $_POST['opciones']);
    }
    
    # Sanitize array with strings
    function test_saniteze_number_in_array_with_strings() {
        SecurityLayer(False)->clean_post_data();
        $expected = array(5, 0, 28);
        $this->assertEquals($expected, $_POST['opciones_con_error']);
    }

}
