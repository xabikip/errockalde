<?php

class Logger {

    public function __construct() {
        $this->mode = 3;
        $this->file = WRITABLE_DIR . "europio.log";
    }

    public function log($data, $tmpl) {
        $msg = Debugger()->get_trace_message($data, $tmpl);
        $msg = str_replace("\n[BACKTRACE]", "", $msg);
        error_log($msg, $this->mode, $this->file);
    }

}


# Alias
function Logger() { return new Logger(); }

?>
