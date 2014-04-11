<?php
/**
* Crea un objeto diccionario iterando recursivamente sobre las propiedades del 
* objeto pasado como parámetro.
*
* @package      EuropioEngine
* @subpackage   core.helpers
* @license      http://www.gnu.org/licenses/gpl.txt  GNU GPL 3.0
* @author       Jimmy Daniel Barranco y Eugenia Bahit
* @link         http://www.europio.org
*/


class Dict {

    public function __construct() { }

    /**
    * Fabrica un objeto de tipo diccionario de forma recursiva, incluyendo el
    * nombre de los objetos en los nombres de las propiedades.
    * Para utilizar en las sustituciones de un template, los comodines deben
    * indicarse como {objeto.propiedad}
    *
    * @param  object $obj objeto a tomar como base para el diccionario
    * @return void
    */
    public function set_dict_for_template($obj) {
        $obj_name = strtolower(get_class($obj));
        foreach($obj as $property=>$value) {
            if(is_array($value)) continue;
            if(!is_object($value)) {
                $property_name = "$obj_name.$property";
                $this->$property_name = $value;
            } else {
                $this->set_dict_for_template($value);
            }
        }
    }

    # Alias for set_dict_for_template()
    public function set($obj) { $this->set_dict_for_template($obj); }


    /**
    * Convierte al array de objetos pasado como parámetro, en un array de
    * objetos para utilizar en las sustituciones de una lista desplegable de 
    * formulario, mediante el plug-in WebForm
    *
    * @param  array $collection colección de objetos a preparar
    * @param  string $text nombre de la propiedad a ser utilizada como texto
    *                      en las opciones del select
    * @param  int $id (opcional) ID del objeto que aparecerá seleccionado por
    *                            defecto en el select
    * @return void
    */
    public static function set_dict_for_webform(&$collection, $text, $id=0) {
        $pid = strtolower(get_class($collection[0])) . "_id";
        foreach($collection as &$obj) {
            if(!property_exists($obj, $pid)) $pid = self::get_real_name($obj);
            $obj->value = $obj->$pid;
            $obj->text = $obj->$text;
            $obj->extras = ($obj->value == $id) ? ' selected' : '';
        }
    }

    private static function get_real_name($obj=array()) {
        $real_name = '';
        foreach($obj as $property=>$value) {
            if(strpos($property, '_id') !== False) {
                $real_name = $property;
                break;
            }
        }
        return $real_name;
    }

}


/**
* Fabrica objetos diccionarios en forma recursiva sobre una colección de objetos
*/
class DictCollection {

    public function __construct() {
        $this->collection = array();
    }

    /**
    * Llama recursivamente a Dict()::set() y almacena la colección de objetos
    * Dict en la propiedad $this->collection
    *
    * @param  array $collection colección de objetos a ser transformados en diccionarios
    * @return void
    */
    public function set($collection=array()) {
        foreach($collection as $obj) {
            $new_object = new Dict();
            $new_object->set($obj);
            $this->collection[] = $new_object;
        }
    }

}

?>
