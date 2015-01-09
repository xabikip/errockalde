<?php

function get_data($campo){
      return isset($_POST[$campo]) ? $_POST[$campo] : null;
}

function validar_requeridos(&$errores=array(), $requeridos=array()){
      foreach ($requeridos as $value) {
            if (get_data($value) == null) $errores[$value]  = "$value " . ERROR_MSG_REQUIERE;
      }
}

function validar_tipoImagen(&$errores=array(), $mime_permitidos=array(), $campoImagen){
      if($_FILES[$campoImagen]['error'] !== 4){
        if($_FILES[$campoImagen]['error'] == 1){
           $errores[$campoImagen] = ERROR_MSG_IMG_MAXSIZE;
        }else if($_FILES[$campoImagen]['error'] == 0){
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mime = $finfo->file($_FILES[$campoImagen]['tmp_name']);
            $permitido = in_array($mime, $mime_permitidos);
            if(!$permitido) {
                $errores[$campoImagen] = ERROR_MSG_MYME_TYPE;
            }
        }else{
            $errores[$campoImagen] = ERROR_MSG_IMG;
        }
      }
}

function guardar_imagen($ruta, $campoImagen){
      if(isset($_FILES[$campoImagen]['tmp_name'])){
                move_uploaded_file($_FILES[$campoImagen]['tmp_name'], $ruta);
      }
}

function validar_formato_mail(&$errores=array(), $campoMail){
         if(!isset($errores[$campoMail])){
            if(!filter_var($_POST[$campoMail], FILTER_VALIDATE_EMAIL)) $errores[$campoMail] = ERROR_MSG_MAIL_FORMAT;
        }
}

function validar_hora(&$errores=array(), $campoHora){
        if(!preg_match("/(2[0-3]|[01][0-9]):[0-5][0-9]/", get_data($campoHora))){
            $errores[$campoHora] = "$campoHora: " . ERROR_MSG_TIME_FORMAT;
        }
}

function render_final_back($str, $titulo='') {
        $template = file_get_contents(CUSTOM_TEMPLATE );
        $dict = array(
            "TITLE"=>$titulo,
            "CONTENIDO"=>$str,
            "user"=>$_SESSION['username']
        );
        $render_final = Template($template)->render($dict);
        print $render_final;
}



?>