<?php

$constants = parse_ini_file(APP_DIR . 'common/config.ini', False);
foreach($constants as $constant=>$value) {
    define($constant, $value);
}

?>
