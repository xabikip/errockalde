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

        ===============================================================================
                                 INSTALACIÓN DE ERROCKALDE
        ===============================================================================
        1. AVISO: nótese que allow_url_fopen debe estar en on en el php.ini

        2. Crear base de datos
           mysql -uroot -p -e "CREATE DATABASE nombredb;"

        3. Crear usuario para la DB de la aplicación
           mysql -uroot -p -e "CREATE USER 'usuario'@'localhost' IDENTIFIED BY 'contraseña';"

        4. Otorgar permisos al usuario de la base de datos, creado:
           mysql -uroot -p -e "USE nombredb; GRANT ALL ON nombredb TO usuario;"

        5. Crear directorio con permisos de escritura

        6. Ejecutar archivo create_uploads_tree.sh

        7. Crear base de datos de usuarios
           cd core/cli
           ./europio --user-setup nombredb

        8. importar archivos SQL
           mysql -u root -p nombredb < appmodules/bazkideak/bazkideak.sql
           mysql -u root -p nombredb < appmodules/ekitaldiak/ekitaldiak.sql
           mysql -u root -p nombredb < appmodules/blog/blog.sql

        9. Copiar config.ini.dist como config.ini

        10. Editar config.ini:
            - datos de la db
            - WRITABLE_DIR
            - PRODUCTION = True


        ===============================================================================
                             REGLAS DE MOD SECURITY A ELIMINAR
        ===============================================================================
        /etc/modsecurity/base_rules/modsecurity_crs_41_sql_injection_attacks.conf
        Lines: 64, 76, 77, 155, 235

        /etc/modsecurity/base_rules/modsecurity_crs_21_protocol_anomalies.conf
        Lines: 47, 48, 65, 66
