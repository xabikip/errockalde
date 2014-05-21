<?php

require_once('settings.php');
require_once('core/helpers/files.php');

$ruta = WRITABLE_DIR . $_GET['dokumentua'];

FileManager::show($ruta);

?>