<?php

require_once('settings.php');
require_once('core/helpers/files.php');

$file = isset($_GET['dokumentua']) ? $_GET['dokumentua'] : 'imagen-por-defecto.gif';
$ruta = WRITABLE_DIR . $file;

FileManager::show($ruta);

?>