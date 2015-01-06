<?php

abstract class Object {

    function __toLog() {
        $GLOBALS['LAST_OBJECT_VARS'][get_class($this)] = get_object_vars($this);
    }

    function __toString() {
        return "<". get_class($this) ." Object>";
    }

}

?>
