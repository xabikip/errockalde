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
            $p = md5(htmlentities(strip_tags($_POST['pwd'])));
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
