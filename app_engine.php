<?php
/**
* EuropioEngine
*
* Motor principal de la aplicación.
* Importa todos los archivos necesarios, del núcleo de la aplicación e
* inicializa el controlador del motor MVC
*
* This file is part of EuropioEngine.
* EuropioEngine is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
* EuropioEngine is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
* You should have received a copy of the GNU General Public License
* along with EuropioEngine.  If not, see <http://www.gnu.org/licenses/>.
*
*
* @package    EuropioEngine
* @license    http://www.gnu.org/licenses/gpl.txt  GNU GPL 3.0
* @author     Eugenia Bahit <ebahit@member.fsf.org>
* @link       http://www.europio.org
* @version    3.4.24
*/

require_once 'settings.php';

import('core.api.server');
import('core.api.client');

import('core.helpers.autoload');
import('core.helpers.patterns');
import('core.helpers.http');
import('core.helpers.template');
import('core.helpers.files');
import('core.helpers.dict');

import('core.libs.europio_code');

import('core.data.datahandler');

import('core.orm_engine.mysqlilayer');

import('core.orm_engine.objects.standardobject');
import('core.orm_engine.objects.serializedobject');
import('core.orm_engine.objects.collectorobject');
import('core.orm_engine.objects.multiplierobject');
import('core.orm_engine.objects.logicalconnector');
import('core.orm_engine.objects.composerobject');

import('core.sessions.handler');

import('core.mvc_engine.controller');
import('core.mvc_engine.front_controller');

import('core.aliases');


# Importación de plugins
$apps = isset($options['PLUGINS']) ? $options['PLUGINS'] : array();
foreach($apps as $plugin=>$enabled) {
    if($enabled) import("common.plugins.$plugin.__init__");
}


# Autocarga de módulos
Autoload::get_installed_modules();

# Archivos del usuario son cargados desde user_imports.php
# Si este archivo no existe en la raíz de la app, debe ser creado
import('user_imports', False);


# Arrancar el motor de la aplicación
FrontController::start();
?>
