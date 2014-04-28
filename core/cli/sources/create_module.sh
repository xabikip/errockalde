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
        mkdir models views controllers helpers
        set_context_files $1
        echo -e "Módulo \033[1m$1\033[22m creado con éxito"
    else
        echo "El módulo $1 ya existe y no puede ser sobreescrito."
        echo "ejecute \"./europio -d $1\" para eliminarlo"
    fi
}


function set_context_files() {
    tmp_path="../../core/cli/templates"

    touch config.ini
    touch config.ini.dist

    # __init__.php
    echo "<?php" > __init__.php
    echo "import('appmodules.$1.settings');" >> __init__.php

    touch $1.sql

    # README
    readme=`replace MODULO $1 < $tmp_path/README`
    echo "$readme" > README

    # settings.php 
    settings=`replace MODULO $1 < $tmp_path/settings`
    echo "$settings" > settings.php
}
