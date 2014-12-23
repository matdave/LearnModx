<?php
/**
 *
 * Copyright 2014 by Thomas Gautvedt <thomasgautv[at]hotmail[dot]com>
 *
 * StaticCollector is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * StaticCollector is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * StaticCollector; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package staticcollector
 */
 
$settings = array();

$settings['staticcollector.staticcollector-path'] = $modx->newObject('modSystemSetting');
$settings['staticcollector.staticcollector-path']->fromArray(array(
    'key' => 'staticcollector.staticcollector-path',
    'value' => '{assets_path}components/staticcollector/',
    'xtype' => 'textfield',
    'namespace' => 'staticcollector',
    'area' => '',
), '', true, true);

return $settings;