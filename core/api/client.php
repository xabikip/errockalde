<?php

class APIClient {

    public static function get($uri='') {
        if(is_callable('curl_init')) {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $uri);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            $json_data = curl_exec($curl);
            curl_close($curl);
            return $json_data;
        } else {
            return file_get_contents($uri);
        }
    }

}

?>
