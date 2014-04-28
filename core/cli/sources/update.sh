#!/bin/bash

################################################################################
########################### EUROPIO ENGINE UPDATER #############################
#                                 Version 1.0                                  #
################################################################################

function get_branch() {
    dir=$HOME/.config
    if [ ! -d $dir ]; then
        mkdir $dir
    fi
    if [ ! -d $dir/europio ]; then
        mkdir $dir/europio
        cd $dir/europio
        echo "Creando un nuevo branch desde el cual actualizar sus aplicaciones"
        bzr branch lp:europioexperimental
        echo "Listo."
        echo ""
    fi
}


function start() {
    get_branch
    if [ ! -d "$1" ]; then
        if [ "$1" != "" ]; then
            echo "$1 no es un directorio. Reintente con otra ruta."
        fi
        echo -n "Ruta física de la app a actualizar: "
        read path_app
        start $path_app
    else
        actualizar $1
    fi
}


function actualizar() {
    dir=$HOME/.config/europio/europioexperimental
    echo "Se actualizará a la última versión disponible de Europio Engine"
    cd $dir
    bzr pull lp:europioexperimental
    echo "Listo."
    echo ""
    echo "Se actualizará el núcleo de su aplicación"
    cd ..
    mv $dir/.bzr $dir/.bzr_original_repo
    cp -R $dir/* $1
    mv $dir/.bzr_original_repo $dir/.bzr
    echo "Tareas finalizadas."
}


start $1
