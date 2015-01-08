#!/usr/bin/php
<?php

delete_file("/home/piko/curso-php/uploads/astinddu/");

function delete_file($ruta){
   if (is_dir($ruta)) {
      if ($dir = opendir($ruta)) {
         while (($file = readdir($dir)) !== false) {
            if(is_dir($ruta . $file) !== true && $file!="." && $file!=".."){
                    //echo "Archivo:$ruta$file \n";
                    if(filesize("$ruta$file") == 0) unlink("$ruta$file");
            }
            if (is_dir($ruta . $file) && $file!="." && $file!=".."){
               delete_file($ruta . $file . "/");
            }
         }
      closedir($dir);
      }
   }
}

?>
