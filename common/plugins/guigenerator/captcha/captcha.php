<?php
/**
* Captcha Generator PlugIn
*
* Generador de captchas para formularios
*
* This file is part of Europio GUIGenerator PlugIn.
* Europio GUIGenerator PlugIn is free software: you can redistribute it and/or
* modify it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
* Europio GUIGenerator PlugIn is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
* You should have received a copy of the GNU General Public License
* along with Europio GUIGenerator PlugIn. If not,
* see <http://www.gnu.org/licenses/>.
*
*
* @package    Europio GUIGenerator PlugIn
* @license    http://www.gnu.org/licenses/gpl.txt  GNU GPL 3.0
* @author     Eugenia Bahit <ebahit@member.fsf.org>
* @link       http://www.europio.org/
* @version    1.0
* @require    EuropioEngine 3.4.24 (o superior)
*/


class Captcha {

    /**
     * Ruta por defecto (desde APP_DIR) de la base de datos
    */
    const DEFAULT_DB = "common/plugins/guigenerator/captcha/database";

    /**
     * Propiedades de acceso público
     * Pueden configurarseantes de invocar a get()
    */
    public $dbfile;
    public $value;

    /**
     * Construir un objeto de tipo Captcha
     *
     * @param  string  $dbfile      Si se desea utilizar una base de datos 
                                    propia, la ruta (desde APP_DIR) de la db
     * @return void
    */
    public function __construct($dbfile='') {
        $this->dbfile = ($dbfile) ? $dbfile : APP_DIR . self::DEFAULT_DB;
        $this->value = $this->get();
    }

    /**
     * Generar una pregunta de seguridad
     * Almacena la respuesta en la variable de sesión 'captcha'
     *
     * @param  void
     * @return string La pregunta de seguridad
    */
    public function get() {
        $database = $this->read();
        $rows = array();
        foreach($database as $row) {
            list($pregunta, $respuesta) = explode('|', $row);
            $rows[] = array($pregunta, $respuesta);
        }
        $random_index = rand(0, count($rows)-1);
        $captcha = $rows[$random_index];
        $_SESSION['captcha'] = $captcha[1];
        return $captcha[0];
    }

    /**
     * Leer el archivo de base de datos y lo parsea en un array de registros
     *
     * @param  void
     * @return array Array de preguntas y respuestas para el captcha
    */
    protected function read() {
        $database = explode(chr(10), file_get_contents($this->dbfile));
        array_pop($database);
        return $database;
    }

}


/**
 * Alias para instanciar al captcha y acceder a un atributo o método en un
   solo paso
 *
 * @example $captcha = Captcha()->value;
 * @param  string  $dbfile      Si se desea utilizar una base de datos 
                                propia, la ruta (desde APP_DIR) de la db
 * @return object  Un objeto de tipo Captcha
*/
function Captcha($dbfile='') { return new Captcha($dbfile); }

?>
