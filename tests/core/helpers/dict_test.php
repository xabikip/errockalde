<?php
$actual_path = ini_get('include_path');
require_once "./settings.php";
require_once "./core/helpers/dict.php";
ini_set('include_path', $actual_path);


class DictTest extends PHPUnit_Framework_TestCase {

    function setUp() {
        $this->biblioteca = new BibliotecaFakeForDict();
        $this->coverage = new Dict();
        $this->coverage->set_dict_for_template($this->biblioteca);
        
        $this->collection = array();
        $i = 1;
        while($i < 5) {
            $std = new stdClass();
            $std->stdclass_id = $i;
            $std->denominacion = "foo";
            $this->collection[] = $std;
            $i++;
        }
        Dict::set_dict_for_webform($this->collection, 'denominacion', 2);
    }

    function test_set_dict_with_bibliotecafakefordict_denominacion() {
        $property = 'bibliotecafakefordict.denominacion';
        $this->assertObjectHasAttribute($property, $this->coverage);
        $this->assertEquals('Library', $this->coverage->$property);
    }
    
    function test_set_dict_with_librofakefordict_denominacion() {
        $property = 'librofakefordict.denominacion';
        $this->assertObjectHasAttribute($property, $this->coverage);
        $this->assertEquals('Book', $this->coverage->$property);
    }
    
    function test_set_dict_with_autorfakefordict_denominacion() {
        $property = 'autorfakefordict.denominacion';
        $this->assertObjectHasAttribute($property, $this->coverage);
        $this->assertEquals('Writer', $this->coverage->$property);
    }
    
    function test_set_dict_with_isbnfakefordict_denominacion() {
        $property = 'isbnfakefordict.codigo';
        $this->assertObjectHasAttribute($property, $this->coverage);
        $this->assertEquals('Code', $this->coverage->$property);
    }
    
    function test_set_dict_without_capitulo_collection() {
        $property = 'librofakefordict.capitulo_collection';
        $this->assertObjectNotHasAttribute($property, $this->coverage);
    }

    function test_set_dict_without_bibliotecafakefordict_collection() {
        $property = 'librofakefordict.bibliotecafakefordict_collection';
        $this->assertObjectNotHasAttribute($property, $this->coverage);
    }

    function test_set_dict_without_collection() {
        $obj_que_espero = new Dict();
        $property = "dict.foo";
        $obj_que_espero->$property = 'bar';
        $espero = print_r($obj_que_espero, True);

        $mock = new Dict();
        $mock->foo = 'bar';
        $mock->collection = array(new stdClass(), new stdClass());
        $coverage = new Dict();
        $coverage->set($mock);
        $recibo = print_r($coverage, True);

        $this->assertEquals($espero, $recibo);
    }

    function test_for_collection() {
        $this->assertCount(4, $this->collection);
        $this->assertObjectHasAttribute('text', $this->collection[1]);
        $this->assertObjectHasAttribute('value', $this->collection[0]);
        $this->assertObjectHasAttribute('extras', $this->collection[2]);
        $this->assertEquals(' selected', $this->collection[1]->extras);
        $this->assertEquals('foo', $this->collection[2]->text);
        $this->assertEquals(3, $this->collection[2]->value);
    }
    
    function test_set_dict_for_collection() {
        $obj1 = new BibliotecaFakeForDict();
        $obj1->denominacion = 'foo 1';
        $obj2 = new BibliotecaFakeForDict();
        $obj2->denominacion = 'foo 2';
        $obj3 = new BibliotecaFakeForDict();
        $obj3->denominacion = 'foo 3';
        $collection = array($obj1, $obj2, $obj3);
        $coverage = new DictCollection();
        $coverage->set($collection);
        
        $this->assertCount(3, $coverage->collection);
        $this->assertObjectHasAttribute('autorfakefordict.denominacion',
            $coverage->collection[0]);
        $this->assertObjectHasAttribute('autorfakefordict.denominacion',
            $coverage->collection[1]);
        $this->assertObjectHasAttribute('autorfakefordict.denominacion',
            $coverage->collection[2]);
            
        $propiedad = "bibliotecafakefordict.denominacion";
        $this->assertEquals('foo 1', $coverage->collection[0]->$propiedad);
        $this->assertEquals('foo 2', $coverage->collection[1]->$propiedad);
        $this->assertEquals('foo 3', $coverage->collection[2]->$propiedad);
    }
}



class AutorFakeForDict {
    function __construct() {
        $this->denominacion = "Writer";
    }
}


class ISBNFakeForDict {
    function __construct() {
        $this->codigo = "Code";
    }
}


class LibroFakeForDict {
    function __construct() {
        $this->denominacion = "Book";
        $this->autorfakefordict = new AutorFakeForDict();
        $this->isbnfakefordict = new ISBNFakeForDict();
        $this->capitulo_collection = array();
    }
}

class BibliotecaFakeForDict {
    function __construct() {
        $this->denominacion = "Library";
        $this->librofakefordict = new LibroFakeForDict();
        $this->bibliotecafakefordict_collection = array(
            new LibroFakeForDict(),
            new LibroFakeForDict(),
            new LibroFakeForDict()
        );
    }
}

?>
