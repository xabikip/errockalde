<?php
/**
* Capa de abstracción a bases de datos MySQL
*
* @package    EuropioEngine
* @subpackage ORMEngine
* @license    http://www.gnu.org/licenses/gpl.txt  GNU GPL 3.0
* @author     Eugenia Bahit <ebahit@member.fsf.org>
* @link       http://www.europio.org
*/


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
        self::$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, $db);
        $chars = (defined('DB_CHARSET')) ? DB_CHARSET : 'utf8';
        self::$conn->set_charset($chars);
    }

    protected static function preparar() {
        self::$stmt = self::$conn->prepare(self::$sql);
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


# Alias para estilo por procedimientos
function mysqli_run_query($sql, $data, $fields=False, $db=False) {
    return MySQLiLayer::ejecutar($sql, $data, $fields, $db);
}

?>
