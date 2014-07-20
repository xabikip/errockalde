<?php

class post extends StandardObject {

    public function __construct(User $name=NULL) {
        $this->post_id = 0;
        $this->titularra = '';
        $this->sortua = '';
        $this->aldatua = '';
        $this->egilea = $name;

    }

}

?>
