#!/usr/bin/php
<?php

$constants = parse_ini_file('config.ini', False);
foreach($constants as $constant=>$value) {
    define($constant, $value);
}
$con = mysql_connect(DB_HOST, DB_USER, DB_PASS);
mysql_select_db(DB_NAME);
mysql_query("DELETE FROM pasahitzberria WHERE data < NOW() - INTERVAL 1440 MINUTE");

?>