<?php
/**
* Conector Lógico Relacional
*
* @package    EuropioEngine
* @subpackage ORMEngine
* @license    http://www.gnu.org/licenses/gpl.txt  GNU GPL 3.0
* @author     Eugenia Bahit <ebahit@member.fsf.org>
* @link       http://www.europio.org
*/
import('core.helpers.patterns');
import('core.orm_engine.corm.helper');
import('core.orm_engine.corm.corm');


class LogicalConnector {

    private static $composername;

    public function __construct($obj=NULL, $compositor='') {
        self::$composername = $compositor;
        $this->connector_id = 0;
        $this->compuesto = $obj;
        $this->collection = CORMHelper::get_collection($obj, $compositor);
    }

    public function destroy() {
        CORM::delete($this->compuesto, self::$composername);
    }

    public function save() {
        $this->destroy();
        if(count($this->collection) > 0) CORM::create($this->compuesto, 
            self::$composername);
    }
    
    public function get() {
        $results = CORM::read($this->compuesto, self::$composername);
        foreach($results as $field) {
            $objchild = Pattern::factory(self::$composername, $field['id']);
            $method = CORMHelper::get_addfunc(self::$composername);
            $this->compuesto->$method($objchild);
        }
    }
}


function LogicalConnector($obj_compuesto=NULL, $cls_compositor='') {
    return new LogicalConnector($obj_compuesto, $cls_compositor);
}

?>
