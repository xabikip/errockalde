#!/bin/bash

# EuropioEngine CLI
# @package    EuropioEngine
# @subpackage core.cli
# @license    http://www.gnu.org/licenses/gpl.txt  GNU GPL 3.0
# @author     Eugenia Bahit <ebahit@member.fsf.org>
# @link       http://www.europio.org


# Imprime menú en pantalla
function __print_menu() {
    echo "Para crear una nueva tabla, indique a qué tipo de objeto pertenece."
    echo "    (m)    Relacional Multiplicador"
    echo "    (c)    Conector Lógico Relacional"
    echo "    (s)    Objeto Serializado"
    echo "    (q)    CANCELAR"
    echo " "
    echo -n "Su opción (m/c/s/q): "
}


# setea el valor del tipo de objeto según elección del usuario
function __set_type() {
    case "$1" in
        q) type='none' ;;
        m) type='multiplier' ;;
        c) type='connector' ;;
        s) type='serialized' ;;
        *) echo "Opción incorrecta"; crear_tabla ;;
    esac
    echo $type
}


# Crear una tabla 
function crear_tabla() {
    __print_menu; read tipo

    type=$(__set_type $tipo)
    if [ "$type" == "none" ]; then
        exit
    fi

    if [ "$type" != "serialized" ]; then
        echo -n "Nombre del Objeto Compuesto: "; read obj
        echo -n "Nombre del Objeto Compositor: "; read compositor
    else
        echo -n "Nombre del Objeto Serializado: "; read obj
        compositor=''
    fi

    if [ "$1" == "FOO" ]; then
        echo -n "Nombre de la base de datos: "; read DB
    else
        echo "Base de datos: $1"
	DB=$1
    fi

    creacion=`php -f scripts/createtable.php $type $obj $compositor`
    echo "$creacion" > .europioengine.sql.tmp
    mysql -uroot -p $DB < .europioengine.sql.tmp
    rm .europioengine.sql.tmp
    echo "Listo!"
    echo -n "¿Desea crear otra tabla en $DB? (s/n) "; read respuesta
    if [ "$respuesta" == "s" ]; then
        crear_tabla $DB
    else
        echo "Hasta luego!"
        exit
    fi
}
