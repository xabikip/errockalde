#!/bin/bash

# EuropioEngine CLI
# @package    EuropioEngine
# @subpackage core.cli
# @license    http://www.gnu.org/licenses/gpl.txt  GNU GPL 3.0
# @author     Eugenia Bahit <ebahit@member.fsf.org>
# @link       http://www.europio.org


# Genera el código para un modelo
function set_model() {
    filename=`php -r "print strtolower('$1');"`
    property=$filename
    contenido=`replace ClassName $1 property $property < templates/model`
    echo "$contenido" > ../../appmodules/$2/models/$filename.php
}


# Genera el código para un controlador
function set_controller() {
    filename=`php -r "print strtolower('$1');"`
    property=$filename
    contenido=`replace MODULO $2 ARCHIVO $filename ClassName $1 property \
        $property < templates/controller`
    echo "$contenido" > ../../appmodules/$2/controllers/$filename.php
}


# Genera el código para una vista
function set_view() {
    filename=`php -r "print strtolower('$1');"`
    contenido=`replace ClassName $1 ARCHIVO $filename MODULO $2 < \
        templates/view`
    echo "$contenido" > ../../appmodules/$2/views/$filename.php
}


# Genera el código para el helper
function set_helper() {
    filename=`php -r "print strtolower('$1');"`
    contenido=`replace MODELO $1 < templates/helper`
    echo "$contenido" > ../../appmodules/$2/helpers/$filename.php
}


# Agrega query al SQL
function set_sql() {
    modelo=`php -r "print strtolower('$1');"`
    contenido=`replace MODELO $modelo < templates/sql`
    echo "$contenido" >> ../../appmodules/$2/$2.sql
}


# Agrega imports al init
function add_imports() {
    filename=`php -r "print strtolower('$1');"`
    modelo="import('appmodules.$2.models.$filename');"
    vista="import('appmodules.$2.views.$filename');"
    helper="import('appmodules.$2.helpers.$filename');"
    echo $modelo >>  ../../appmodules/$2/__init__.php
    echo $vista >>  ../../appmodules/$2/__init__.php
    echo $helper >>  ../../appmodules/$2/__init__.php
}


# Crear los archivos models, views y controllers
function create_files() {
    modulo=`php -r "print strtolower('$1');"`
    if [ ! -d "../../appmodules/$modulo" ]; then
        echo "El módulo $1 no existe. Puede crearlo con ./europio -c $1"
    else
        set_model $2 $1
        set_controller $2 $1
        set_view $2 $1
        set_helper $2 $1
        set_sql $2 $1
        add_imports $2 $1
        echo "Listo!"
    fi
}
