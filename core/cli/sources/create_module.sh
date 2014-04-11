#!/bin/bash

# EuropioEngine CLI
# @package    EuropioEngine
# @subpackage core.cli
# @license    http://www.gnu.org/licenses/gpl.txt  GNU GPL 3.0
# @author     Eugenia Bahit <ebahit@member.fsf.org>
# @link       http://www.europio.org


# Crear estructura de directorios para un nuevo módulo del sistema
function create_module() { # $1 = modulo
    ruta='../../appmodules'
    cd $ruta
    if [ ! -d "$1" ]; then
        mkdir $1; cd $1
        mkdir models views controllers
        echo -e "Módulo \033[1m$1\033[22m creado con éxito"
    else
        echo "El módulo $1 ya existe y no puede ser sobreescrito."
        echo "ejecute \"./europio -d $1\" para eliminarlo"
    fi
}
