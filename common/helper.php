<?php

function get_data($campo){
      return isset($_POST[$campo]) ? $_POST[$campo] : null;
}

function validar_requeridos($errores=array(), $requeridos=array()){
     foreach ($requeridos as $value) {
            if (get_data($value) == null) $errores[$value]  = "$value beharrezkoa da";
        }
     return $errores;
}

function validar_tipoImagen($errores=array(), $tipo_permitido=array(), $tipo, $campoImagen){
      if (!in_array($tipo, $tipo_permitido) AND $_FILES[$campoImagen]['error'] !== 4){
                $errores[$campoImagen] = "Formatua ez da egokia.";
            }
      return $errores;
}

function guardar_imagen($ruta, $campoImagen){
      if(isset($_FILES[$campoImagen]['tmp_name'])){
                move_uploaded_file($_FILES[$campoImagen]['tmp_name'], $ruta);
      }
}

?>