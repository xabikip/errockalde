<?php

function get_data($campo){
      return isset($_POST[$campo]) ? $_POST[$campo] : null;
}

?>