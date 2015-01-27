<?php

class postHelper {

    public static function data_tartean($data1, $data2){
        $sql = "SELECT * FROM post WHERE sortua BETWEEN ? AND ?";
        $data = array('ss', "$data1", "$data2");
        $fields = array(
                        "post_id"=>"",
                        "titularra"=>"",
                        "sortua"=>"",
                        "urtea"=>"",
                        "hilabetea"=>"",
                        "aldatua"=>"",
                        "slug"=>"",
                        "kategoria"=>"",
                        "user"=>"");
        $results = MySQLiLayer::ejecutar($sql, $data, $fields);
        foreach ($results as $obj) {
              $posts[] = Pattern::factory('post', $obj['post_id'] );
        }
        return $posts;
    }

}

?>
