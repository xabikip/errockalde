<?php
/**
* Capa de abstracción a bases de datos MySQL
*
* package    EuropioEngine
* subpackage ORMEngine
* license    http://www.gnu.org/licenses/gpl.txt  GNU GPL 3.0
* author     Eugenia Bahit <ebahitmember.fsf.org>
* link       http://www.europio.org
*/
import('core.dev_tools.error_handler');


class MySQLiLayer {

    protected static $conn;
    protected static $stmt;
    protected static $reflection;
    protected static $sql;
    protected static $data;
    public static $results = array();

    protected static function conectar($dbname=False) {
        $db = (!$dbname) ? DB_NAME : $dbname;
        if(defined('DB_TESTING')) $db = DB_TESTING;
        @self::$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, $db);
        $errno = self::$conn->connect_errno;
        if($errno > 0) {
            MySQLiErrorHandler()->brief($errno);
        }
        $chars = (defined('DB_CHARSET')) ? DB_CHARSET : 'utf8';
        self::$conn->set_charset($chars);
    }

    protected static function preparar() {
        self::$stmt = self::$conn->prepare(self::$sql);
        if(!self::$stmt) {
            self::$conn->close();
            MySQLiErrorHandler()->handle();
        }
        self::$reflection = new ReflectionClass('mysqli_stmt');
    }
    
    protected static function set_params() {
        $method = self::$reflection->getMethod('bind_param'); 
        $method->invokeArgs(self::$stmt, self::$data);
    }
    
    protected static function get_data($fields) {
        $method = self::$reflection->getMethod('bind_result');
        $method->invokeArgs(self::$stmt, $fields);
        self::$results = array();
        
        while(self::$stmt->fetch()) {
            self::$results[] = unserialize(serialize($fields));
        }
    }

    protected static function finalizar() {
        self::$stmt->close();
        self::$conn->close();
    }

    public static function ejecutar($sql, $data, $fields=False, $db=False) {
        self::$sql = $sql;
        self::$data = $data;
        self::conectar($db);
        self::preparar();
        self::set_params();
        self::$stmt->execute(); 
        if($fields) {
            self::get_data($fields);
            return self::$results;
        } else {
            if(strpos(strtoupper(self::$sql), 'INSERT') === 0) {
                return self::$stmt->insert_id;
            } else {
                return self::$stmt->affected_rows;
            }
        }
        self::finalizar();
    }

}


class MySQLiErrorHandler extends ErrorHandler {

    function brief($errno) {
        switch($errno) {
            case 1045:
                $msg = "Usuario o clave de MySQL incorrecta";
                $const = "DB_USER y DB_PASS";
                break;
            case 1049:
                $db = DB_NAME;
                $user = DB_USER;
                $msg = "No existe la DB '$db' para el user '$user'";
                $const = "DB_NAME";
                break;
            case 2005:
                $msg = "'". DB_HOST . "' no es un servidor de DB accesible";
                $const = "DB_HOST";
                break;
        }
        $data = array(
          "date"=>date("d/m/Y H:i:s"),
          "client"=>$_SERVER['REMOTE_ADDR'],
          "error"=>$msg,
          "const"=>$const
        );
        Debugger(false)->trace($data, 'mysqli_connerror');
        Logger()->log($data, 'mysqli_connerror');
        exit();
    }

    function handle($mode=EH_BOTH) {
        extract(self::get_tmp_vars());
        $data = array(
          "date"=>date("d/m/Y H:i:s"),
          "client"=>$_SERVER['REMOTE_ADDR'],
          "uri"=>DevToolsHelper::get_real_uri(),
          "model"=>DevToolsHelper::get_model_name(),
          "clsvars_str"=>DevToolsHelper::array_to_string($clsvars),
          "fields_str"=>$fields_str,
          "objvars_str"=>DevToolsHelper::array_to_string($objvars),
          "model2table"=>$m2t_str,
          "object2table"=>$o2t_str,
          "object2model"=>DevToolsHelper::get_diff_to_string($objvars, $clsvars)
        );

        $both = ($mode == EH_BOTH);
        $debug = ($both || $mode == EH_DEBUG);
        $log = ($both || $mode == EH_LOG);
        if($debug) Debugger()->trace($data, 'mysqli_sqlerror');
        if($log) Logger()->log($data, 'mysqli_sqlerror');
        exit();
    }

    private static function help_text_no_table() {
        $table = strtolower(DevToolsHelper::get_model_name());
        return "La DB '". DB_NAME ."' no tiene una tabla '$table'";
    }

    private static function get_tmp_vars() {
        $notbl = self::help_text_no_table();

        $clsvars = DevToolsHelper::get_class_properties();
        $fields = DevToolsHelper::get_fields_from_table();
        $objvars = DevToolsHelper::get_object_properties();

        list($fields_str, $m2t_str, $o2t_str) = array_fill(0, 3, $notbl);

        if(!empty($fields)) {
            $fields_str = DevToolsHelper::array_to_string($fields);
            $m2t_str = DevToolsHelper::get_diff_to_string($clsvars, $fields);
            $o2t_str = DevToolsHelper::get_diff_to_string($objvars, $fields);
        }
        
        return get_defined_vars();
    }

}


# Alias para estilo por procedimientos
function mysqli_run_query($sql, $data, $fields=False, $db=False) {
    return MySQLiLayer::ejecutar($sql, $data, $fields, $db);
}


# Otros Alias
function MySQLiErrorHandler() { return new MySQLiErrorHandler(); }

?>
