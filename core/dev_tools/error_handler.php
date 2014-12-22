<?php

const EH_DEBUG = 1;
const EH_LOG = 2;
const EH_BOTH = 3;


abstract class ErrorHandler { 

    function __construct() {
        if(PRODUCTION) HTTPHelper::exit_by_ee1001();
    }

}

?>
