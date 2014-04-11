<?php
/**
* Clase que provee de diferentes patrones de diseño
*
* @package    EuropioEngine
* @subpackage core.helpers
* @license    http://www.gnu.org/licenses/gpl.txt  GNU GPL 3.0
* @author     Eugenia Bahit <ebahit@member.fsf.org>
* @link       http://www.europio.org
*/

class Pattern {

    /**
    * Fabrica un objeto (Factory Design Pattern)
    *
    * @param  string $cls Nombre de la clase
    * @param  int $id_value ID del objeto a ser recuperado
    * @param  string $idname (opcional) Nombre de la propiedad ID del objeto
    * @return object Objeto fabricado
    */
    public static function factory($cls, $id_value, $idname='') {
        $objid = ($idname) ? $idname : strtolower($cls) . "_id";
        $obj = new $cls();
        $obj->$objid = $id_value;
        $obj->get();
        return $obj;
    }

    /**
    * Compone un objeto (Composite Design Pattern)
    *
    * @param  string $cls Nombre de la clase
    * @param  string $obj Objeto compositor
    * @return object $obj si es una instancia de $cls o de lo contrario, error
    */
    public static function composite($cls, $obj) {
        try {
            if(!is_a($obj, $cls)) {
                $class_name = get_class($obj);
                throw new Exception("$class_name no es del tipo $cls");
            } else {
                return $obj;
            }
        } catch (Exception $exception) {
            print "Error fatal: " . $exception->getMessage();
            exit();
        }
    }

    /**
    * Agrega un compositor a un compuesto mediante su método de agregación
    * correspondiente
    *
    * El nombre del método de agregación debe ser add_nombre_del_compositor
    * por ejemplo, para agregar un objeto A al objeto B, será B::add_a(A $a)
    *
    * @param  object        $compuesto El objeto que contiene el método de 
    *                                  agregación
    *
    * @param  string|array  $compositores El objeto a ser agregado o un array de
    *                                     objetos
    */
    public static function aggregation($compuesto, $compositores) {
        if(!is_array($compositores)) $compositores = array($compositores);
        foreach($compositores as $compositor) {
            $method = "add_" . get_class($compositor);
            $compuesto->$method($compositor);
        }
    }

}

?>
