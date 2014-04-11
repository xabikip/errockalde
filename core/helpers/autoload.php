<?php

class Autoload {

    public static function get_installed_modules() {
        if(AUTOLOAD_MODULE) {
            $appmodules_dir = APP_DIR . "appmodules";
            $installed_modules = scandir($appmodules_dir);
            foreach($installed_modules as $module) {
                if(!in_array($module, array('.', '..'))) {
                    $autoload_file = "$appmodules_dir/$module/__init__.php";
                    if(file_exists($autoload_file)) include_once $autoload_file;
                }
            }
        }
    }

}

?>
