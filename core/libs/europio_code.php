<?php
/*
* EuropioCode PHP
*
* Codificador y decodificador de código.
*
* Codifica cadenas de texto convirtiendo caracteres no alfanuméricos en pseudo
* codigo, sanitizando así, cualquier campo de formulario previo a su
* envío. Luego, decodifica el pseudocódigo convirtiéndolo en entidades 
* hexadecimales de HTML.
* Utilizado de forma conjunta con ModSecurity y las reglas de OWASP,
* lograrán formularios invulnerables con aplicaciones 100% funcionales, gracias
* a su deodificador que interpretará el código de forma tal, que sean evitados
* los falsos positivos de ModSecurity. 
*
* EuropioCode is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
* EuropioCode is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
* You should have received a copy of the GNU General Public License
* along with Foobar.  If not, see <http://www.gnu.org/licenses/>.
*
*
* @package    EuropioCode
* @license    http://www.gnu.org/licenses/gpl.txt  GNU GPL 3.0
* @author     Eugenia Bahit <ebahit@member.fsf.org>
* @link       http://www.europio.org
* @version    beta 1.3
*/


class EuropioCode {

    # Tabla numérica de equivalencias para entidades html hexadecimales
    protected static $tbl01;

    # Tabla de tags HTML permitidos en textos con preformato
    protected static $tbl02;

    # Tabla de codificación de caracteres de tratamiento especial
    protected static $tbl03;

    # Tabla de codificación para hiperenlaces
    protected static $tbl04;

    # Tabla de caracteres no alfanuméricos a conservar en una limpieza
    protected static $tbl05;

    # Prefijo de codificación para caracteres de tratamiento especial
    protected static $special_char_prefix = "ECOD";

    # Prefijo de codificación para el preformato
    protected static $preformat_prefix = "pFt";


    # =====================================================================
    # TABLA NUMÉRICA DE EQUIVALENCIAS PARA ENTIDADES HTML HEXADECIMALES
    # =====================================================================
    protected static function set_base_table() {
        self::$tbl01 = array(
          '!'=>33, '"'=>34, '#'=>35, '$'=>36, '%'=>37, '&'=>38, "'"=>39,
          '('=>40, ')'=>41, '*'=>42, '+'=>43, ','=>44, '.'=>46, '/'=>47,
          ':'=>58, '<'=>60, '='=>61, '>'=>62, '?'=>63, '@'=>64, '['=>91,
          '\\'=>92, ']'=>93, '^'=>94, '_'=>95, '`'=>96, '{'=>123, '|'=>124,
          '}'=>125, '~'=>126, '€'=>128, ' '=>160, '¡'=>161, '£'=>163, '«'=>171,
          '´'=>180, '·'=>183, '»'=>187, '¿'=>191, 'Ç'=>199, 'ç'=>231, '-'=>45,
          ';'=>59, '\n'=>13, 'Á'=>193, 'É'=>201, 'Í'=>205, 'Ó'=>211, 'Ú'=>218,
          'Ü'=>220, 'á'=>225, 'é'=>233, 'í'=>237, 'ó'=>243, 'ú'=>250, 'ü'=>252,
          'Ñ'=>209, 'ñ'=>241
        );
    }


    # =====================================================================
    # TABLA DE TAGS HTML PERMITIDOS EN TEXTOS CON PREFORMATO
    # =====================================================================
    protected static function set_preformat_table() {
        self::$tbl02 = array(
            '<b>', '<strong>', '<i>', '<em>', '<u>', 
            '<strike>', '<sub>', '<sup>',
            '<p>', '<blockquote>', '<hr>',
            '<ul>', '<ol>', '<li>',
            '<h1>', '<h2>', '<h3>', '<h4>', '<h5>', '<h6>',
            '<code>', '<pre>', '<br>', '<small>'
        );
    }


    # =====================================================================
    # TABLA DE CODIFICACIÓN DE CARACTERES DE TRATAMIENTO ESPECIAL
    # =====================================================================
    protected static function set_especial_chars_table() {
        $prefix = self::$special_char_prefix;

        self::$tbl03 = array(
            "{$prefix}G"=>'&#', "{$prefix}C"=>';', "{$prefix}S"=>"\n"
        );
    }


    # =====================================================================
    # TABLA DE CODIFICACIÓN PARA HIPERENLACES
    # =====================================================================
    protected static function set_hyperlink_table() {
        self::$tbl04 = array(
            '>'=>'fT0x1', '"'=>'', '<a href='=>'aH0n2',
            'target=_'=>'tG0n7', '://'=>'pT7n3', '/'=>'bB0n1',
            '~'=>'nN0n5', '.'=>'p01nt', '-'=>'gN6n1'
        );
    }


    # =====================================================================
    # TABLA DE CARACTERES NO ALFANUMÉRICOS A CONSERVAR EN UNA LIMPIEZA
    # =====================================================================
    protected static function set_preserve_table() {
        self::$tbl05 = array(
            '&#225;'=>'a', '&#233;'=>'e', '&#237;'=>'i', '&#243;'=>'o',
            '&#250;'=>'u', '&#252;'=>'u',
            '&#193;'=>'A', '&#201;'=>'E', '&#205;'=>'I', '&#211;'=>'O',
            '&#218;'=>'U', '&#220;'=>'U',
            '&#241;'=>'n', '&#209;'=>'N',
            '&#231;'=>'c', '&#199;'=>'C', '&#160;'=>' '
            
        );
    }


# ******************************************************************************


    /**
    * Codifica una cadena en formato EuropioCode desde su estado original
    *
    * @param  (string) $cadena    -- la cadena a ser codificada
    * @return (string) $resultado -- la cadena codificada
    */
    public static function encode($cadena) {
        self::set_base_table();
        $prefix = self::$special_char_prefix;

        $resultado = str_replace("\n", "{$prefix}S", $cadena);

        foreach(self::$tbl01 as $caracter=>&$numero) {
            $numero = "{$prefix}G{$numero}{$prefix}C";
        }

        $resultado = str_replace(
            array_keys(self::$tbl01), array_values(self::$tbl01), $resultado);

        return $resultado;
    }


    /**
    * Decodifica una cadena en formato EuropioCode a sus entidades HTML
    *
    * @param  (string) $cadena  -- la cadena a ser decodificada
    * @param  (string) $tipo_salto -- (opcional) 'br' para aplicar etiquetas HTML
    * @return (string) $retorno -- la cadena convertida a entidades HTML
    */
    public static function decode($cadena, $tipo_salto='') {
        self::set_especial_chars_table();

        $retorno = str_replace(
            array_keys(self::$tbl03), array_values(self::$tbl03), $cadena);

        $pf = self::$preformat_prefix;
        $regex = "/$pf(e)?[0-9]{1,2}/";
        $retorno = preg_replace($regex, "", $retorno);
        return ($tipo_salto == 'br') ? nl2br($retorno) : $retorno;
    }


    /**
    * Revierte una cadena decodificada a su estado original
    *
    * @param  (string) $cadena -- la cadena codificada a ser revertida
    * @return (string) $resultado -- la cadena revertida en su estado original
    */
    public static function reverse($cadena) {
        self::set_base_table();

        $resultado = $cadena;
        foreach(self::$tbl01 as $caracter=>$numero) {
            $codigo = "&#$numero;";
            $resultado = str_replace($codigo, $caracter, $resultado);
        }

        return str_replace("&#09;", "\t", $resultado);
    }
    
    
    /**
    * Decodifica una cadena de texto preformateado respetando los tags HTML
    * permitidos
    *
    * @param  (string) $cadena  -- la cadena a ser decodificada
    * @return (string) $retorno -- la cadena decodificada
    */
    public static function decode_preformat($cadena) {
        self::set_preformat_table();

        $resultado = self::decode_hyperlink($cadena);
        for($i=0; $i<count(self::$tbl02); $i++) {
            $numero = ($i < 10) ? "0$i" : $i;

            $pft_apertura = self::$preformat_prefix . $numero;
            $tag_apertura = self::$tbl02[$i];
            $resultado = str_replace($pft_apertura, $tag_apertura, $resultado);
            
            $pft_cierre = self::$preformat_prefix . "e{$numero}";
            $tag_cierre = str_replace("<", "</", $tag_apertura);
            $resultado = str_replace($pft_cierre, $tag_cierre, $resultado);
        }
        
        $decode = self::decode($resultado);
        return str_replace("&#160;", " ", $decode);
    }


    /**
    * Decodificar los hiperenlaces para una cadena de texto con preformato
    *
    * @param  (string) cadena -- cadena codificada
    * @return (string) cadena -- la cadena con los enlaces decodificados
    */
    protected static function decode_hyperlink($cadena) {
        self::set_hyperlink_table();

        $res = str_replace(
            array_values(self::$tbl04), array_keys(self::$tbl04), $cadena);
        return str_replace("eT0n1", "</a>", $res);
    }


    /**
    * Limpia una cadena previamente decodificada.
    * Elimina cualquier caracter no alfanumérico, reemplazando previamente
    * vocales acentuadas, diéresis en las U, eñes y cedillas por su equivalente
    *
    * @param  (string) $cadena  -- la cadena previamente decodificada
    * @return (string) $result  -- la cadena limpiada con espacios en blanco 
    *                              sobrantes removidos
    */
    public static function clean($cadena) {
        self::set_preserve_table();

        $result = str_replace(array_keys(
            self::$tbl05), array_values(self::$tbl05), $cadena);
        $result = preg_replace("/&#[0-9]{2,5};/", " ", $result);
        $result = preg_replace("/[^a-zA-Z0-9]/", " ", $result);
        $result = preg_replace("/(\ ){2,}/", " ", $result);
        return $result;
    }


    /**
    * Purificar una cadena previamente decodificada.
    * Elimina cualquier caracter no alfanumérico, reemplazando previamente
    * vocales acentuadas, diéresis en las U, eñes y cedillas por su equivalente.
    * Elimina palabras duplicadas y de longitud inferior a 3 caracteres
    *
    * @param  (string) $cadena  -- la cadena previamente decodificada
    * @return (string) $result  -- la cadena purgada convertida a minúsculas
    *                              con espacios en blanco sobrantes, palabras 
    *                              duplicadas y de menos de 3 chars removidos
    */
    public static function purge($cadena) {
        $cleaned = strtolower(self::clean($cadena));
        $words = explode(" ", $cleaned);
        $unique = array_unique($words, SORT_STRING);
        $result = array();
        foreach($unique as $word) {
            if(strlen($word) > 2) { $result[] = $word; }
        }
        return implode(" ", $result);
    }
}

?>
