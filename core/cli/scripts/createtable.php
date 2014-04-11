<?php
/**
* Generador/Renderer de queries para creación de tablas
*
* @package    EuropioEngine
* @subpackage core.cli
* @license    http://www.gnu.org/licenses/gpl.txt  GNU GPL 3.0
* @author     Eugenia Bahit <ebahit@member.fsf.org>
* @link       http://www.europio.org
*/


if(count($argv) < 2) exit("ERROR: Argumentos insuficientes\n");
foreach($argv as &$value) $value = strtolower($value);

$type = $argv[1];  # Tipo de objeto: multiplier, connector, serialized
$compuesto = $argv[2];  # Nombre del objeto compuesto
$obj = $compuesto;  # Para un objeto serializado, toma el 3er argumento
$compositor = (count($argv) > 3) ? $argv[3] : '';  # Nombre del compositor

$templates = file_get_contents('templates/queries');  # Templates de los queries
$comodines = array("<compositor>", "<compuesto>", "<obj>");
$sustitutos = array($compositor, $compuesto, $obj);
$regex = "/<$type>(.|\n){1,}<\/$type>/";

preg_match($regex, $templates, $matches);
if(count($matches) < 1) exit("ERROR: Tipo de objeto incorrecto\n");

$match = str_replace(array("<$type>", "</$type>"), "\r", $matches[0]);
print str_replace($comodines, $sustitutos, $match);

?>
