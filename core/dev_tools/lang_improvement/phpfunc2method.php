<?php

/*
    Func2Method - Fase experimental 
    (
    Desactivar: 
        No necesita ser desactivada ya que solo funciona si se la invoca
    )

    Permite llamar a cualquier función de PHP como si se tratara de un método.

    Por ejemplo:

        print PHP()->str_replace('foo', 'bar', 'foobar');

    genera el mismo efecto que:

        print str_replace('foo', 'bar', 'foobar')

*/

class PHP {

    function __construct() {
        $funcs = get_defined_functions();
        $this->builtin_functions = $funcs['internal'];
    }

    function __call($func, $args) {
        if(in_array($func, $this->builtin_functions)) {
            return call_user_func_array($func, $args);
        } else {
            PHPFunc2MethodErrorHandler()->brief($func);
            return NULL;
        }
    }

}


class PHPFunc2MethodErrorHandler {

    function brief($func) {
        $data = array(
            "date"=>date('Y-m-d H:i:s'),
            "client"=>"developer on {$_SERVER['REQUEST_URI']}",
            "error"=>"PHP() Class no posee una función llamada $func"
        );
        Logger()->log($data, 'common_error');
        if(!PRODUCTION) print $data['error'];
    }

}


# Alias
function PHPFunc2MethodErrorHandler() { return new PHPFunc2MethodErrorHandler(); }
function PHP() { return new PHP(); }

?>
