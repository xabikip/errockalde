#!/bin/bash
echo -n "Ruta: "; read ruta
# Ejemplo: /home/user/carpeta/subdirectorio
# (sin barra al final)

mkdir -p $ruta/bazkideak/taldea/{azalak,bandcamp,irudiak,youtube}
mkdir -p $ruta/blog/post/{edukiak,irudiak,parrafoak}
mkdir -p $ruta/ekitaldiak/ekitaldia/kartelak

chmod -R 777 $ruta