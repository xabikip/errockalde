#!/bin/bash

# EuropioEngine CLI
# @package    EuropioEngine
# @subpackage core.cli
# @license    http://www.gnu.org/licenses/gpl.txt  GNU GPL 3.0
# @author     Eugenia Bahit <ebahit@member.fsf.org>
# @link       http://www.europio.org


# Eliminar estructura de directorios completa de un módulo existente del sistema
function delete_module() {
    ruta='../../appmodules'
    cd $ruta
    abspath=`pwd`
    todostr="$abspath/\033[1m1\033[22m"
    completo="$abspath/$1"
    if [ -d "$completo" ]; then
        echo "Se eliminarán todos los archivos del directorio:"
        echo -e $todostr
        rm -rI $1
        if [ ! -d "$completo" ]; then
            echo "Módulo $1 eliminado con éxito"
        else
            echo "Se ha conservado el módulo $1"
        fi
    else
        echo "El módulo $1 no existe"
    fi
}
