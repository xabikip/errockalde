<?php
$actual_path = ini_get('include_path');
require_once "./settings.php";
require_once "./core/helpers/template.php";
ini_set('include_path', $actual_path);


require_once "common/plugins/collectorviewer/__init__.php";


$nombres = array('juan', 'pepe', 'susi', 'ana', 'emilia', 'ricardo', 'noemi',
    'patricia', 'felipe', 'corina', 'karina', 'elisa', 'carlos', 'pablo',
    'noelia', 'natalia', 'jazmin', 'gabriel', 'david', 'igor', 'ivan', 'mario',
    'rogelio');
    

class CollectorViewerTest extends PHPUnit_Framework_TestCase {

    function setUp() {
        $this->collection = array();
        $this->mockear_coleccion();
        $this->coverage = new CollectorViewer($this->collection);
    }

    function mockear_coleccion() {
        global $nombres;
        foreach($nombres as $i=>$nombre) {
            $this->collection[] = new CollectorViewerFake($i+1, $nombre);
        }
    }

    function test_verificar_columnas_cabeceras_expecting_2() {
        $espero = "<!--tdtitle--><th>USER ID</th><!--tdtitle-->";
        $espero .= "<!--tdtitle--><th>NOMBRE</th><!--tdtitle-->";
        $this->assertEquals($espero, $this->coverage->get_thcols());
    }

    function test_verificar_columnas_cabeceras_en_tabla() {
        $regex = "/<thead><tr><th>USERID<\/th><th>NOMBRE<\/th>/";
        $this->coverage->render_thcols();
        $recibido = str_replace("\n", "", $this->coverage->table);
        $recibido = str_replace(" ", "", $recibido);
        $this->assertRegExp($regex, $recibido);
    }
    
    function test_verificar_columnas_cuerpo() {
        $espero = "<!--tdvalue--><td>{user_id}</td><!--tdvalue-->";
        $espero .= "<!--tdvalue--><td>{nombre}</td><!--tdvalue-->";
        $this->assertEquals($espero, $this->coverage->get_tdcols());
    }
    
    function test_verificar_columnas_cuerpo_en_tabla() {
        $regex = "/<tr><td>\{user_id}\<\/td><td>\{nombre}\<\/td>/";
        $this->coverage->render_tdcols();
        $recibido = str_replace("\n", "", $this->coverage->table);
        $recibido = str_replace(" ", "", $recibido);
        $this->assertRegExp($regex, $recibido);
    }

    function test_verificar_filas() {
        $regex1 = "/<tr><td>1<\/td><td>juan<\/td>(.|\n){1,}editar\/1/";
        $regex2 = "/<tr><td>2<\/td><td>pepe<\/td>(.|\n){1,}eliminar\/2/";
        $this->coverage->render_rows();
        $recibido = str_replace("\n", "", $this->coverage->table);
        $recibido = str_replace(" ", "", $recibido);
        $this->assertRegExp($regex1, $recibido);
        $this->assertRegExp($regex2, $recibido);
    }

    function test_verificar_propiedad_id() {
        $this->coverage->alter_collection();
        $this->assertObjectHasAttribute('id', $this->coverage->collection[0]);
        $this->assertObjectHasAttribute('id', $this->coverage->collection[10]);
    }
    
    function test_verificar_tabla_final_con_funcion_alias() {
        $regex1 = "/\/modulo\/modelo\/editar\/17/";
        $regex2 = "/\/modulo\/modelo\/eliminar\/5/";
        $tabla = CollectorViewer($this->collection, 'modulo', 'modelo')->get_table();
        $this->assertRegExp($regex1, $tabla);
        $this->assertRegExp($regex2, $tabla);
    }
    
    function test_verificar_buttons_property() {
        $this->assertArrayHasKey('ver', $this->coverage->buttons);
    }
    
    function test_verificar_buttons_keys() {
        $tbl = CollectorViewer($this->collection, 'modulo', 'modelo',
            True, False, True);
        $this->assertTrue($tbl->buttons['ver']);
        $this->assertFalse($tbl->buttons['editar']);
        $this->assertTrue($tbl->buttons['eliminar']);
    }
    
    function test_verificar_boton_ver_expecting_false() {
        $obj = new CollectorViewer($this->collection, 'modulo', 'modelo', False);
        $regex = "/<!--ver-->(.|\n){1,}<!--ver-->/";
        $this->assertNotRegExp($regex, $obj->table);
    }
    
    function test_verificar_botones_expecting_tff() {
        $obj = new CollectorViewer($this->collection, 'modulo', 'modelo', True,
            False, False);
        $regex1 = "/<!--ver-->(.|\n){1,}<!--ver-->/";
        $this->assertRegExp($regex1, $obj->table);
        $regex2 = "/<!--editar-->(.|\n){1,}<!--editar-->/";
        $this->assertNotRegExp($regex2, $obj->table);
        $regex3 = "/<!--eliminar-->(.|\n){1,}<!--eliminar-->/";
        $this->assertNotRegExp($regex3, $obj->table);
    }
}


class CollectorViewerFake {

    function __construct($id, $nombre) {
        global $nombres;
        $this->user_id = $id;
        $this->nombre = $nombre;
    }
}
?>
