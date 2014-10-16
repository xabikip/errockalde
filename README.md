#Errockalde

Plataforma online para la organización interna de un colectivo de grupos de música
creada con [Europio Engine](http://www.europio.org/).


##REQUERIMIENTOS:

        - Apache 2 sobre GNU/Linux
        - PHP 5.3.10 (o posterior)
        - Módulo MySQL para PHP 5 (php5-mysql)
        - PHP CLI (php5-cli)
        - MySQL 5.5 (o posterior)

        Si desea correr los test, necesitará además:
        - PHPUnit 3.7.19 (o posterior)

        Para mantener actualizado Europio Engine, necesita:
        - Bazaar 2.5 (bzr)

##Europio Engine

        REPOSITORIO OFICIAL:
        https://launchpad.net/europioexperimental

        REPORTE DE BUGS:
        https://bugs.launchpad.net/europioexperimental/+filebug

        ASISTENCIA DE LA COMUNIDAD:
        https://answers.launchpad.net/europioexperimental
        Hacer una consulta:
        https://answers.launchpad.net/europioexperimental/+addquestion


##Instalación

        Una vez que tengas el entorno preparado y Europio Engine descargado, estos
        serian los pasos a seguir.

        1.- Reemplazar en .htaccess original por este.

        2.- Configurar el config.ini.dist dejandolo en config.ini

        3.- Añadir dokumentuak.php en la raiz de la aplicación.

        4.- Añadir o sobreescribir urls.php

        5.- Reemplazar el user_imports.php

        6.- Añadir los modulos bazkideak, blog y ekitaldiak dentro de appmodules.

        7.- Leemplazar la carpeta commons original por esta.

        8.- Añadir dentro de static la carpeta errockalde.

        Con esto ya tiene todo lo necesario para que corra la aplicación. Ahora
        necesitara crear la BD. Una vez creado la BD dentro de cada modulo tienes
        su respectivo .sql para crear las tablas que necesite. Ejecuta estos .sql
        y podra empezar a utilizar la aplicación.
