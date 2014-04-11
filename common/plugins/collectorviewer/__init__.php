<?php
/**
* Europio CollectorViewer PlugIn
*
* Generador de tablas HTML para listado de objetos en EuropioEngine
*
* This file is part of Europio CollectorViewer PlugIn.
* Europio CollectorViewer PlugIn is free software: you can redistribute it 
* and/or modify it under the terms of the GNU General Public License as 
* published by the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
* Europio CollectorViewer PlugIn is distributed in the hope that it will be 
* useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
* You should have received a copy of the GNU General Public License
* along with Foobar.  If not, see <http://www.gnu.org/licenses/>.
*
*
* @package    Europio CollectorViewer PlugIn
* @license    http://www.gnu.org/licenses/gpl.txt  GNU GPL 3.0
* @author     Eugenia Bahit <ebahit@member.fsf.org>
* @link       http://www.europio.org/plugins/CollectorViewer
* @version    1.0 beta 1
* @require    EuropioEngine 3.2 (o superior)
*/


class CollectorViewer {

    function __construct($collection=array(), $modulo='', $modelo='',
            $ver=True, $editar=True, $eliminar=True) {
        $this->collection = $collection;
        $this->action = "/$modulo/$modelo";
        $this->table = file_get_contents(
            APP_DIR . "common/plugins/collectorviewer/collectorviewer.html");
        $this->buttons = array('ver'=>$ver, 'editar'=>$editar,
            'eliminar'=>$eliminar);
        $this->set_buttons();
    }
    
    function set_buttons() {
        foreach($this->buttons as $tipoboton=>$mostrar) {
            if(!$mostrar) {
                $fuente1 = Template($this->table)->get_regex($tipoboton, False);
                $fuente2 = Template($this->table)->get_regex("th{$tipoboton}",
                    False);
                $tbl = str_replace(array($fuente1, $fuente2), "", $this->table);
                $this->table = $tbl;
            }
        }
    }

    function get_thcols() {
        $cadena = "";
        $fuente = Template($this->table)->get_regex('tdtitle', False);
        if(isset($this->collection[0])) {
            foreach($this->collection[0] as $k=>$v) {
                $title = str_replace("_", " ", strtoupper($k));
                $cadena .= str_replace("{tdtitle}", $title, $fuente);
            }
        }

        return $cadena;
    }
    
    function render_thcols() {
        $columnas = str_replace('<!--tdtitle-->', '', $this->get_thcols());
        $fuente = Template($this->table)->get_regex('tdtitle', False);
        $this->table = str_replace($fuente, $columnas, $this->table);
    }
    
    function get_tdcols() {
        $cadena = "";
        $fuente = Template($this->table)->get_regex('tdvalue', False);
        if(isset($this->collection[0])) {
            foreach($this->collection[0] as $k=>$v) {
                $cadena .= str_replace("{tdvalue}", "{{$k}}", $fuente);
            }
        }

        return $cadena;
    }
    
    function render_tdcols() {
        $columnas = str_replace('<!--tdvalue-->', '', $this->get_tdcols());
        $fuente = Template($this->table)->get_regex('tdvalue', False);
        $this->table = str_replace($fuente, $columnas, $this->table);
    }

    function alter_collection() {
        foreach($this->collection as &$obj) {
            foreach($obj as $property=>$value) {
                if(strpos($property, '_id') > 0) $obj->id = $value;
            }
        }
    }

    function render_rows() {
        $this->render_thcols();
        $this->render_tdcols();
        $this->alter_collection();
        $this->table = Template($this->table)->render_regex('listado',
            $this->collection);
    }
    
    function get_table() {
        $this->render_rows();
        $dict = array('action'=>$this->action,
            'tableid'=>"EuropioCViewer_" . spl_object_hash($this));
        $this->table = Template($this->table)->render($dict);
        return $this->table;
    }
}


# Alias para compatibilidad pseudo estática 
function CollectorViewer($collection=array(), $modulo='', $modelo='',
    $ver=True, $editar=True, $eliminar=True) {
    return new CollectorViewer($collection, $modulo, $modelo, $ver, $editar,
        $eliminar);
}
?>
