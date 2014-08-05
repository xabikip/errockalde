<?php

/*
 *
 * Basado en Easybook slugger
 *
 * slugger library is licensed under the MIT license.
 * Copyright (c) 2014 Javier Eguiluz javier.eguiluz@gmail.com
 * Para ver licencia: https://github.com/easybook/slugger
 *
 *
 */

class Slugger{

    protected $separator;

    public function __construct($separator = null){

        $this->separator = null === $separator ? '-' : $separator;
    }


    public function slugify($string, $separator = null){

        $separator = null === $separator ? $this->separator : $separator;

        $slug = trim(strip_tags($string));
        $slug = $this->transliterate($slug);
        $slug = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $slug);
        $slug = strtolower(preg_replace("/[\/_|+ -]+/", $separator, $slug));
        $slug = trim($slug, $separator);

        return $slug;
    }


    /**
     *
     *
     * @param  string $string The string to transliterate
     * @return string         The safe Latin-characters-only transliterated string
     */
    private function transliterate($string){

        $characterMap = array(
            // Latin
            'À' => 'A', 'Á' => 'A',  'Â' => 'A',  'Ã' => 'A', 'Ä' => 'A',
            'Å' => 'A', 'Æ' => 'AE', 'Ç' => 'C',  'È' => 'E', 'É' => 'E',
            'Ê' => 'E', 'Ë' => 'E',  'Ì' => 'I',  'Í' => 'I', 'Î' => 'I',
            'Ï' => 'I', 'Ð' => 'D',  'Ñ' => 'N',  'Ò' => 'O', 'Ó' => 'O',
            'Ô' => 'O', 'Õ' => 'O',  'Ö' => 'O',  'Ő' => 'O', 'Ø' => 'O',
            'Ù' => 'U', 'Ú' => 'U',  'Û' => 'U',  'Ü' => 'U', 'Ű' => 'U',
            'Ũ' => 'U', 'Ý' => 'Y',  'Þ' => 'TH', 'ß' => 'ss',
            'à' => 'a', 'á' => 'a',  'â' => 'a',  'ã' => 'a',  'ä' => 'a',
            'å' => 'a', 'æ' => 'ae', 'ç' => 'c',  'è' => 'e',  'é' => 'e',
            'ê' => 'e', 'ë' => 'e',  'ì' => 'i',  'í' => 'i',  'î' => 'i',
            'ï' => 'i', 'ð' => 'd',  'ñ' => 'n',  'ò' => 'o',  'ó' => 'o',
            'ô' => 'o', 'õ' => 'o',  'ö' => 'o',  'ő' => 'o',  'ø' => 'o',
            'ù' => 'u', 'ú' => 'u',  'û' => 'u',  'ü' => 'u',  'ű' => 'u',
            'ũ' => 'u', 'ý' => 'y',  'þ' => 'th', 'ÿ' => 'y',

        );

        return str_replace(array_keys($characterMap), $characterMap, $string);
    }
}