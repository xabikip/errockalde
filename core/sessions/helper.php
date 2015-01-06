<?php


class SessionHelper {

    public static function get_user() {
        $u = "";
        if(isset($_POST['user'])) {
            $u = htmlentities(strip_tags($_POST['user']));
        }
        return $u;
    }

    public static function get_pwd() {
        $p = "";
        if(isset($_POST['pwd'])) {
            if(defined('SECURITY_LAYER_ENCRYPT_PASSWORD')) {
                if(!SECURITY_LAYER_ENCRYPT_PASSWORD) {
                    $p = md5(EuropioCode::reverse($_POST['pwd']));
                } else {
                    $p = EuropioCode::reverse($_POST['pwd']);
                }
            }
        }
        return $p;
    }

    public static function set_query() {
        $sql = "SELECT user_id, name, level FROM user
                WHERE name = ? AND pwd = ?";
        return $sql;
    }
}

?>
