<?php
/**
* Base de creación para objetos estándar
*
* @package    EuropioEngine
* @subpackage ORMEngine
* @license    http://www.gnu.org/licenses/gpl.txt  GNU GPL 3.0
* @author     Eugenia Bahit <ebahit@member.fsf.org>
* @link       http://www.europio.org
*/
import('core.orm_engine.orm.handler');
import('core.orm_engine.orm.orm');
import('core.helpers.patterns');


abstract class StandardObject {

    function save() {
        list($vars, $tbl, $idname) = ORMHandler::analize($this);
        if($this->$idname == 0) {
            $this->$idname = ORM::create($this);
        } else {
            ORM::update($this);
        }
    }

    function get() {
        $data = ORM::read($this);
        foreach($data as $property=>$value) {
            if(is_null($this->$property) && !is_null($value)) {
                $this->$property = Pattern::factory(ucwords($property), $value);
            } else {
                $this->$property = $value;
            }
        }
        $this->set_collections();
    }
    
    function destroy() {
        ORM::delete($this);
    }

    private function set_collections() {
        $properties = get_object_vars($this);
        foreach($properties as $name=>$value) {
            if(is_array($value)) {
                $cls_name = str_replace("_collection", "", ucwords($name));
                $compositor = new $cls_name();
                $parent = get_parent_class($compositor);
                if($parent == "StandardObject") {
                    $lc = new LogicalConnector($this, $cls_name);
                    $lc->get();
                } elseif($parent == "ComposerObject") {
                    $compositor->compose($this);
                }
            }
        }
    }

    /**
     *  Previene errores cuando el objeto no posee un método de agregación
     *  para una propiedad colectora
     */
    function __call($call, $arg) {
        if(strpos($call, 'add_') === 0 && $arg) {
            $cls = str_replace('add_', '', $call);
            $property = "{$cls}_collection";
            if(!property_exists($this, $property)) $this->$property = array(); 
            $this->$property = array_merge($this->$property, $arg);
        }
    }

}

?>
