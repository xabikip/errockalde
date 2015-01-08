#!/usr/bin/php
<?php

$constants = parse_ini_file('config.ini', False);
define('DB_HOST', $constants['DB_HOST']);
define('DB_USER', $constants['DB_USER']);
define('DB_PASS', $constants['DB_PASS']);
define('DB_NAME', $constants['DB_NAME']);


$con = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8", DB_USER, DB_PASS);
$con->query("DELETE FROM pasahitzberria WHERE data < NOW() - INTERVAL 1440 MINUTE");
$con = null;

?>