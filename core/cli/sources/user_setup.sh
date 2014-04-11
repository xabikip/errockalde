#!/bin/bash

# EuropioEngine CLI
# @package    EuropioEngine
# @subpackage core.cli
# @license    http://www.gnu.org/licenses/gpl.txt  GNU GPL 3.0
# @author     Eugenia Bahit <ebahit@member.fsf.org>
# @link       http://www.europio.org


# Setup de la DB de usuarios
function user_setup() {
    echo " Se creará la tabla user en la base de datos $1"
    echo " A continuación, indique los datos de acceso para el administrador"
    echo " "
    echo -n " Nombre de usuario: "
    read useradmin
    echo -n " Contraseña administración: "
    read -s clave
    echo " "
    echo " "
    echo " A continuación, deberá indicar la contraseña del root de MySQL"
    psswd=`php -r "print md5('$clave');"`
    contenido=`replace UNAME $useradmin PWD $psswd < templates/user-setup`
    echo "$contenido" > .europioengine.user.sql.tmp
    mysql -u root -p $1 < .europioengine.user.sql.tmp
    rm .europioengine.user.sql.tmp
    echo " Listo!"
}
