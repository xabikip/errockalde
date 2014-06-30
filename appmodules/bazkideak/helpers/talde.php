<?php

const SLASH_DBL = "&#47;&#47;";
const FINAL_TAG = "&#34;&#62;";
const BY = "&#160;by&#160;";
const FINAL_ENDTAG = "&#60;&#47;";
const AMP = "&#46;";
const SLASH = "&#47;";


class ParserTalde {

    public static function parse($array, $pattern, $sub_pattern, $pos=1) {
        if(count($array) > 0) {
            $sub_array = explode($pattern, $array[1]);
            if(count($sub_array) > 0) {
                $sub_array = explode($sub_pattern, $sub_array[$pos]);
                return ($pos) ? array_shift($sub_array) : array_pop($sub_array);
            }
        }
    }

}


class TaldeHelper {


}

?>
