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
 
$plugins = array();

/* create the plugin object */
$plugins[0] = $modx->newObject('modPlugin');
$plugins[0]->set('id', 1);
$plugins[0]->set('name', 'StaticCollector');
$plugins[0]->set('description', 'Syncs files with resource in Modx');
$plugins[0]->set('plugincode', getSnippetContent($sources['elements'] . 'plugins/plugin.staticcollector.php'));
$plugins[0]->set('category', 0);

$events = include $sources['events'] . 'events.staticcollector.php';
if (is_array($events) && !empty($events)) {
    $plugins[0]->addMany($events);
    $modx->log(xPDO::LOG_LEVEL_INFO, 'Packaged in ' . count($events) . ' Plugin Events for Static Collector.'); flush();
} else {
    $modx->log(xPDO::LOG_LEVEL_ERROR, 'Could not find plugin events for Static Collector!');
}

unset($events);
return $plugins;