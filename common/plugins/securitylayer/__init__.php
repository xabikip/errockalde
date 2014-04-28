<?php


class SecurityLayer {

    public function __construct($strict=False) {
        $this->strict = $strict;
    }

    public function clean_post_data() {
        foreach($_POST as $key=>$value) {
            $array = (is_array($value));
            if($array) $this->sanitize_array($key);
            if($this->strict && !$array) $this->remove_and_convert($key);
            if(strpos($key, 'mail') !== False) $this->purge_email($key);
            if(is_numeric(str_replace(',', '', $value))
                ) $this->sanitize_number($key);
            if(!$array) $this->encode_string($key);
        }
    }

    public function remove_and_convert($key='') {
        $_POST[$key] = htmlentities(strip_tags($_POST[$key]));
    }

    public function encode_string($key='') {
        if(!self::is_password($key)) {
            $options = array('flags'=>FILTER_FLAG_ENCODE_LOW);
            $_POST[$key] = filter_var($_POST[$key],
                FILTER_SANITIZE_SPECIAL_CHARS, $options);
        } else {
            self::hashing_password($key);
        }
    }

    private static function is_password($key='') {
        $password_names = array('pass', 'clave', 'contrasenia');
        foreach($password_names as $name) {
            if(strpos($key, $name) !== False) return True;
        }
    }

    private static function hashing_password($key='') {
        if(SECURITY_LAYER_ENCRYPT_PASSWORD) {
            $hash = SECURITY_LAYER_ENCRYPT_PASSWORD_HASH;
            $_POST[$key] = hash($hash, $_POST[$key]);
        }
    }

    public function purge_email($key='') {
        $_POST[$key] = filter_var($_POST[$key], FILTER_SANITIZE_EMAIL);
    }

    public function sanitize_number($key='') {
        $pos_colon = strpos($_POST[$key], ',');
        $pos_dot = strpos($_POST[$key], '.');
        $has_colon = ($pos_colon !== False);
        $has_dot = ($pos_dot !== False);
        $filterid = FILTER_VALIDATE_FLOAT;

        if($has_colon && $has_dot) {
            if($pos_colon > $pos_dot) {
                $this->helpernum('.', '', $key);
                $this->helpernum(',', '.', $key);
            } else {
                $this->helpernum(',', '', $key);
            }
        } elseif($has_colon xor $has_dot) {
            $this->helpernum(',', '.', $key);
            settype($_POST[$key], 'float');
        } else {
            settype($_POST[$key], 'integer');
            $filterid = FILTER_VALIDATE_INT;
        }

        $_POST[$key] = filter_var($_POST[$key], $filterid);
    }

    private function helpernum($search, $replace, $key) {
        $_POST[$key] = str_replace($search, $replace, $_POST[$key]);
    }

    private function sanitize_array($key) {
        if(defined('SECURITY_LAYER_SANITIZE_ARRAY')) {
            if(SECURITY_LAYER_SANITIZE_ARRAY) {
                foreach($_POST[$key] as &$value) settype($value, 'int');
            }
        }
    }
}


# Alias para llamada e instancia en un solo paso
function SecurityLayer($strict=False) {
    return new SecurityLayer($strict);
}


# Activación y limpieza automática si SECURITY_LAYER_ENGINE = 'On'
if(defined('SECURITY_LAYER_ENGINE')) {
    if(ucwords(SECURITY_LAYER_ENGINE) == 'On') {
        $strict = SECURITY_LAYER_STRICT_MODE;
        $sanitize_array = SECURITY_LAYER_SANITIZE_ARRAY;
        SecurityLayer($strict)->clean_post_data($sanitize_array);
    }
}
?>
