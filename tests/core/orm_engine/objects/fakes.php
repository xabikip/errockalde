<?php

define('DB_TESTING', 'europio_testing');


class Compuesto extends StandardObject {

    function __construct(Foo $foo=NULL) {
        $this->compuesto_id = 0;
        $this->simple = '';
        $this->compositor_collection = array();
        $this->exclusivo_collection = array();
        $this->foo = $foo;
    }
    
    function add_compositor(Compositor $obj) {
        $this->compositor_collection[] = $obj;
    }
    
    function add_exclusivo(Exclusivo $obj) {
        $this->exclusivo_collection[] = $obj;
    }

}

class Foo extends StandardObject {

    public function __construct() {
        $this->foo_id = 1;
        $this->name = 'foo';
    }

    public function __clone() {
        $this->foo_id++;
    }

}

class Compositor extends StandardObject {

    public function __construct() {
        $this->compositor_id = 1;
        $this->name = 'compositor';
    }

    public function __clone() {
        $this->compositor_id++;
    }

}

class Exclusivo extends ComposerObject {

    public function __construct() {
        $this->exclusivo_id = 1;
        $this->name = 'lala';
        $this->compuesto = 0;
    }

    public function __clone() {
        $this->exclusivo_id++;
    }

}

class STFake extends StandardObject {

    function __construct() {
        $this->stfake_id = 1;
        $this->name = 'bar';
        $this->user_collection = array();
        $this->foo_collection = array();
    }

    function add_user(User $user) {
        $this->user_collection[] = $user;
    }

    function add_foo(Foo $foo) {
        $this->foo_collection[] = $foo;
    }
}


class User extends StandardObject {

    public function __construct() {
        $this->user_id = 1;
        $this->name = 'foo';
    }

    public function __clone() {
        $this->user_id++;
    }

}


?>
