<?php

function get_data($campo){
      return isset($_POST[$campo]) ? $_POST[$campo] : null;
}

function validar_requeridos($errores=array(), $requeridos=array()){
     foreach ($requeridos as $value) {
            if (get_data($value) == null) $errores[$value]  = ERROR_MSG_REQUIERE;
        }
     return $errores;
}

function validar_tipoImagen($errores=array(), $tipo_permitido=array(), $campoImagen){
      $tipo = isset($_FILES[$campoImagen]['type']) ? $_FILES[$campoImagen]['type'] : "image/jpg";
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

function validar_formato_mail($errores=array(), $campoMail){
        if(!$errores){
            if(!filter_var($_POST[$campoMail], FILTER_VALIDATE_EMAIL)) $errores[$campoMail] = ERROR_MSG_MAIL_FORMAT;
        }
        return $errores;
}

function validar_hora($errores=array(), $campoHora){
        if(!preg_match("/(2[0-3]|[01][0-9]):[0-5][0-9]/", get_data($campoHora))){
            $errores[$campoHora] = ERROR_MSG_MAIL_FORMAT;
        }
        return $errores;
}

?>