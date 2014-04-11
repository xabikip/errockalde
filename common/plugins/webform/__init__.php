<?php
/**
* Europio WebForm PlugIn
*
* Generador de formularios HTML para EuropioEngine
*
* This file is part of Europio WebForm PlugIn.
* Europio WebForm PlugIn is free software: you can redistribute it and/or 
* modify it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
* Europio WebForm PlugIn is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
* You should have received a copy of the GNU General Public License
* along with Foobar.  If not, see <http://www.gnu.org/licenses/>.
*
*
* @package    Europio WebForm PlugIn
* @license    http://www.gnu.org/licenses/gpl.txt  GNU GPL 3.0
* @author     Eugenia Bahit <ebahit@member.fsf.org>
* @link       http://www.europio.org/plugins/webform
* @version    1.1.0
* @require    EuropioEngine 3.0 beta 8 (o superior)
*/

class WebForm {

    function __construct($action='.', $method='POST') {
        $this->form_template = file_get_contents(
            APP_DIR . "common/plugins/webform/webform.html");
        $this->action = ($action != "") ? $action : ".";
        $this->method = $method;
        $this->fields = array();
    }

    private function add_field($str, $dict) {
        $this->fields[] = Template($str)->render($dict);
    }
    
    private function set_id($name) {
        return $name . count($this->fields);
    }

    private function set_labeltext($text, $name) {
        return ($text != "") ? "{$text}:" : ucwords($name) . ":";
    }

    private function set_extras($extras) {
        return ($extras) ? " $extras" : "";
    }

    private function get_input() {
        return Template($this->form_template)->get_regex("INPUT");
    }

    private function get_label() {
        return Template($this->form_template)->get_regex("LABEL");
    }

    private function get_select() {
        return Template($this->form_template)->get_regex("SELECT");
    }

    private function get_label_and_input() {
        return $this->get_label() . $this->get_input();
    }

    function add_text($name, $text='', $value='') {
        $dict = array(
            "fieldname"=>$name,
            "fieldid"=>$this->set_id($name),
            "type"=>"text",
            "value"=>$value,
            "extras"=>"",
            "labeltext"=>$this->set_labeltext($text, $name),
        );
        $this->add_field($this->get_label_and_input(), $dict);
    }

    function add_password($name, $text='') {
        $dict = array(
            "fieldname"=>$name,
            "fieldid"=>$this->set_id($name),
            "type"=>"password",
            "value"=>"",
            "extras"=>"",
            "labeltext"=>$this->set_labeltext($text, $name),
        );
        $this->add_field($this->get_label_and_input(), $dict);
    }

    function add_file($name, $text='', $extras="") {
        $dict = array(
            "fieldname"=>$name,
            "fieldid"=>$this->set_id($name),
            "type"=>"file",
            "value"=>"",
            "extras"=>$this->set_extras($extras),
            "labeltext"=>$this->set_labeltext($text, $name),
        );
        $this->add_field($this->get_label_and_input(), $dict);
    }

    function add_hidden($name='', $value) {
        $dict = array(
            "fieldname"=>$name,
            "fieldid"=>$this->set_id($name),
            "type"=>"hidden",
            "value"=>$value,
            "extras"=>""
        );
        $hidden = str_replace("<br>", "", $this->get_input());
        $this->add_field($hidden, $dict);
    }

    function add_submit($value="Enviar") {
        $dict = array(
            "fieldname"=>"",
            "fieldid"=>$this->set_id('btSubmit'),
            "type"=>"submit",
            "value"=>$value,
            "extras"=>""
        );
        $this->add_field($this->get_input(), $dict);
    }

    function prepare_select($name, $text) {
        $dict = array(
            "fieldname"=>$name,
            "fieldid"=>$this->set_id($name),
            "type"=>"text",
            "labeltext"=>$this->set_labeltext($text, $name),
        );
        $str = $this->get_label() . $this->get_select();
        return Template($str)->render($dict);
    }

    function add_select($name, $options=array(), $text='') {
        $select = $this->prepare_select($name, $text);
        $this->fields[] = Template($select)->render_regex('OPTION', $options);
    }

    function add_checkbox($groupname, $options=array(), 
            $title='Seleccione las opciones de su preferencia') {
        $this->add_choices("checkbox", $groupname, $options, $title);
    }

    function add_radio($groupname, $options=array(), 
            $title='Seleccione la opción de su preferencia') {
        $this->add_choices("radio", $groupname, $options, $title);
    }

    function add_choices($type, $groupname, $options, $title) {
        $label = $this->get_label();
        $chk = Template($this->form_template)->get_regex(
            'CHECKBOX-RADIO', False);
        $str = $label . nl2br(chr(10)) . $chk;
        $render = Template($str)->render_regex('CHECKBOX-RADIO', $options);
        $dict = array(
            "type"=>$type,
            "groupname"=>"$groupname",
            "fieldid"=>"",
            "labeltext"=>$this->set_labeltext($title, "")
        );
        $render = ($type == "radio") ? str_replace("[]", "", $render) : $render;
        $this->add_field($render, $dict);
    }

    function add_textarea($name, $value='', $text="Mensaje") {
        $dict = array(
            "fieldname"=>$name,
            "fieldid"=>$this->set_id($name),
            "value"=>$value,
            "extras"=>"",
            "labeltext"=>$this->set_labeltext($text, $name),
        );
        $txt = Template($this->form_template)->get_regex('TEXTAREA');
        $label = $this->get_label() . nl2br(chr(10));
        $this->add_field($label . $txt, $dict);
    }

    function show() {
        $frm = Template($this->form_template)->get_regex('FORM');
        $dict = array(
            "formid"=>"EuropioWebForm_" . spl_object_hash($this),
            "method"=>$this->method,
            "action"=>$this->action,
            "fields"=>implode(chr(10), $this->fields)
        );
        $render = Template($frm)->render($dict);
        return str_replace(
            array("{extras}", "placeholder=\"{labeltext}\""), "", $render);
    }

}

?>
