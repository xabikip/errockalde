<?php
/**
* Objeto Colector
*
* @package    EuropioEngine
* @subpackage ORMEngine
* @license    http://www.gnu.org/licenses/gpl.txt  GNU GPL 3.0
* @author     Eugenia Bahit <ebahit@member.fsf.org>
* @link       http://www.europio.org
*/
import('core.orm_engine.orm.handler');
import('core.orm_engine.sorm.helper');
import('core.helpers.patterns');


class CollectorObject {

    private static $instance;

    private function __construct() {
        $this->collection = array();
    }

    private function add_object($obj) {
        $this->collection[] = $obj;
    }

    public static function get($class_name='Anonymous') {
        $cls = "{$class_name}Collection";

        if(!isset(self::$instance[$cls])) {
            eval("class $cls extends CollectorObject {}");
            self::$instance[$cls] = new $cls();
        }

        $sql = CollectorHelper::set_query($class_name);
        $data = array("i", "0");
        $fields = array("id"=>"");
        $results = MySQLiLayer::ejecutar($sql, $data, $fields);

        self::$instance[$cls]->collection = array();

        foreach($results as $r) {
            self::$instance[$cls]->add_object(
                Pattern::factory($class_name,$r['id']));
        }

        return self::$instance[$cls];
    }

}


class CollectorHelper {

    private static function get_names($cls='') {
        $parent = get_parent_class($cls);
        if($parent == 'StandardObject' || $parent == 'ComposerObject') {
            list($vars, $tbl, $idname) = ORMHandler::analize(new $cls());
            unset($vars);
        } elseif($parent == 'SerializedObject') {
            list($tbl, $idname) = SORMHelper::get_names(new $cls());
        }
        
        return array($tbl, $idname);
    }
    
    public static function set_query($cls='') {
        list($tbl, $idname) = self::get_names($cls);
        return "SELECT $idname FROM $tbl WHERE $idname > ?";
    }
}

?>
