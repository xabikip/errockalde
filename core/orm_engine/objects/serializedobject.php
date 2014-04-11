<?php
/**
* Base de creación para objetos freezados
*
* @package    EuropioEngine
* @subpackage ORMEngine
* @license    http://www.gnu.org/licenses/gpl.txt  GNU GPL 3.0
* @author     Eugenia Bahit <ebahit@member.fsf.org>
* @link       http://www.europio.org
*/
import('core.orm_engine.sorm.helper');
import('core.orm_engine.sorm.sorm');


abstract class SerializedObject {

    public function save() {
        list($tbl, $idname) = SORMHelper::get_names($this);
        if($this->$idname == 0) {
            $this->$idname = SORM::create($this);
            $this->save();
        } else {
            SORM::update($this);
        }
    }
    
    public function get() {
        $fields = SORM::read($this);
        list($tbl, $idname) = SORMHelper::get_names($this);
        if($fields[$idname] != 0) {
            $obj = unserialize($fields[$tbl]);
            foreach($obj as $property=>$value) $this->$property = $value;
        }
    }

    public function destroy() {
        SORM::delete($this);
    }
} 

?>
