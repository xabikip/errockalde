<?php

/*
    Class2Func - Fase experimental 
    (
    Desactivar: 
        ENABLE_CLASS2FUNC = False
    )

    
    PARA DESACTIVAR SU IMPLEMENTACIÓN ESTABLEZCA EN FALSE LA CONSTANTE
    ENABLE_CLASS2FUNC DEL ARCHIVO CONFIG.INI

    Crea una función alias para todas las clases definidas en el sistema,
    incluyendo las incorporadas en el lenguaje.
    Si una función ya existe, pasa de largo y continúa con las demás.

    Esta funcionalidad permite instanciar una clase e invocar uno de sus
    métodos en el mismo paso (como en PHP 5.4 pero omitiendo la palabra new)

    Por ejemplo:

        print DateTime()->format('c');

    genera el mismo efecto que:

        $date = new DateTime();
        print $date->format('c');

    o que en PHP 5.4 lo genera:

        print new DateTime()->format('c');

*/

$_func_template = "
function CLASE() {
    \$args = (func_num_args()) ? func_get_args() : null;
    return @ new CLASE(\$args);
}
";

$_class_to_func = function() use ($_func_template) {

    $clases = get_declared_classes();

    foreach($clases as $clase) {
        $alias = str_replace('CLASE', $clase, $_func_template);
        if(!function_exists($clase)) eval($alias);
    }
};


if(defined('ENABLE_CLASS2FUNC')) { if(ENABLE_CLASS2FUNC) $_class_to_func(); }

?>
