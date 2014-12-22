<?php

class Debugger {

    function __construct($backtrace=true) {
        $this->backtrace = $backtrace;
    }

    function debug($msg) {
        if(!PRODUCTION) {
            header("Content-Type: text/plain; charset=utf-8");
            if($this->backtrace) {
                print "[BACKTRACE]\n\n";
                debug_print_backtrace();
            } else {
                print $msg;
            }
        }
    }

    function get_trace_message($data, $tmpl) {
        $file = APP_DIR . "core/dev_tools/templates/debugger/$tmpl";
        if(!file_exists($file)) $file = $tmpl;
        $tmpl = file_get_contents($file);
        extract($data);
        eval("\$msg = \"$tmpl\";");
        return $msg;
    }

    function trace($data, $tmpl) {
        $msg = $this->get_trace_message($data, $tmpl);
        $this->debug($msg);
    }
}


# Alias
function Debugger($backtrace=true) { return new Debugger($backtrace); }

?>
