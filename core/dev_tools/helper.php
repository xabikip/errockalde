<?php

class DevToolsHelper {

    # Obtener nombre del modelo
    static function get_model_name() {
        $uri = self::get_real_uri();
        $parts = explode('/', $uri);
        return isset($parts[2]) ? ucwords($parts[2]) : 'AnonimousClass';
    }

    # Obtener la URI real
    static function get_real_uri() {
        $uri = $_SERVER['REQUEST_URI'];
        eval(file_get_contents(APP_DIR . "urls.php"));
        $escape = str_replace("/", '\/', $uri);
        $escape = "/^{$escape}/";
        if(isset($urls[$escape])) $uri = $urls[$escape];
        return $uri;
    }

    # Obtener propiedades del modelo original
    static function get_class_properties() {
        $model = self::get_model_name();
        $obj = new $model(); 
        $vars = get_object_vars($obj);
        foreach($vars as $key=>$value) {
            if(is_null($value)) $value = new $key();
            $vars[$key] = gettype($value);
        }
        return $vars;
    }

    # Obtener los campos de la tabla
    static function get_fields_from_table() {
        $table = strtolower(self::get_model_name());
        $scheme = DataHelper::get_scheme($table);
        $fields = array();
        foreach($scheme as $array) {
            $fields[$array['column']] = $array['type'];
        }
        return $fields;
    }

    # Obtener las propiedades de la instancia del modelo
    static function get_object_properties() {
        $model = self::get_model_name();
        global $LAST_OBJECT_VARS;
        $GLOBAL = $LAST_OBJECT_VARS;
        return isset($GLOBAL[$model]) ? $GLOBAL[$model] : array();
    }

    # Retornar string con las claves de un array separadas por pipe
    static function array_to_string($array) {
        return join(' | ', array_keys($array));
    }

    # Retornar string con las claves de $b que no están en $a
    static function get_diff_to_string($a, $b) {
        $str = NULL;
        $diff = array_diff_key($a, $b);
        if(!empty($b)) $str = join(' | ', array_flip($diff));
        return (count($diff) == 0) ? '--' : $str;
    }

}


class AnonimousClass { }

?>
