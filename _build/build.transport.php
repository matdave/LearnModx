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

function getSnippetContent($filename) {
    $o = file_get_contents($filename);
    $o = str_replace('<?php','',$o);
    $o = str_replace('?>','',$o);
    $o = trim($o);
    return $o;
}
 
$mtime = microtime();
$mtime = explode(" ", $mtime);
$mtime = $mtime[1] + $mtime[0];
$tstart = $mtime;
set_time_limit(0);

/* define package */
define('PKG_NAME','LearnModx');
define('PKG_NAME_LOWER','learnmodx');
define('PKG_VERSION','0.0.1');
define('PKG_RELEASE','dev');

/* define sources */
$root = dirname(dirname(__FILE__)).'/';
$sources= array (
    'root' => $root,
    'build' => $root .'_build/',
    'resolvers' => $root . '_build/resolvers/',
    'data' => $root . '_build/data/',
    'properties' => $root . '_build/data/properties/',
    'events' => $root . '_build/data/events/',
    'source_core' => $root . 'core/components/' . PKG_NAME_LOWER,
    'source_assets' => $root . 'assets/components/' . PKG_NAME_LOWER,
    'lexicon' => $root . 'core/components/' . PKG_NAME_LOWER . '/lexicon/',
    'elements' => $root . 'core/components/' . PKG_NAME_LOWER . '/elements/',
    'docs' => $root . 'core/components/' . PKG_NAME_LOWER . '/docs/',
);
unset($root);

/* override with your own defines here (see build.config.sample.php) */
require_once $sources['build'] . 'build.config.php';
require_once MODX_CORE_PATH . 'model/modx/modx.class.php';

$modx= new modX();
$modx->initialize('mgr');
$modx->setLogLevel(modX::LOG_LEVEL_INFO);
$modx->setLogTarget('ECHO'); echo '<pre>'; flush();

$modx->loadClass('transport.modPackageBuilder', '', false, true);
$builder = new modPackageBuilder($modx);
$builder->createPackage(PKG_NAME_LOWER, PKG_VERSION, PKG_RELEASE);
$builder->registerNamespace(PKG_NAME_LOWER, false, true,'{core_path}components/' . PKG_NAME_LOWER . '/', '{assets_path}components/' . PKG_NAME_LOWER . '/');

/* add plugins */
$plugins = include $sources['data'] . 'transport.plugins.php';
if (!is_array($plugins)) { 
    $modx->log(modX::LOG_LEVEL_FATAL, 'Adding plugins failed.');
}
$attributes= array(
    xPDOTransport::UNIQUE_KEY => 'name',
    xPDOTransport::PRESERVE_KEYS => false,
    xPDOTransport::UPDATE_OBJECT => true,
    xPDOTransport::RELATED_OBJECTS => true,
    xPDOTransport::RELATED_OBJECT_ATTRIBUTES => array (
        'PluginEvents' => array(
            xPDOTransport::PRESERVE_KEYS => true,
            xPDOTransport::UPDATE_OBJECT => false,
            xPDOTransport::UNIQUE_KEY => array('pluginid', 'event'),
        ),
    ),
);
foreach ($plugins as $plugin) {
    $vehicle = $builder->createVehicle($plugin, $attributes);
    $builder->putVehicle($vehicle);
}
$modx->log(modX::LOG_LEVEL_INFO,'Packaged in ' . count($plugins) . ' plugins.'); flush();
unset($plugins, $plugin, $attributes);

/* load system settings */
$modx->log(modX::LOG_LEVEL_INFO, 'Packaging in System Settings...');
$settings = include $sources['data'] . 'transport.settings.php';
if (empty($settings)) $modx->log(modX::LOG_LEVEL_ERROR, 'Could not package in settings.');
$attributes = array(
    xPDOTransport::UNIQUE_KEY => 'key',
    xPDOTransport::PRESERVE_KEYS => true,
    xPDOTransport::UPDATE_OBJECT => false,
);
foreach ($settings as $setting) {
    $vehicle = $builder->createVehicle($setting, $attributes);
    $builder->putVehicle($vehicle);
}
unset($settings, $setting, $attributes);

$vehicle->resolve('file',array(
    'source' => $sources['source_core'],
    'target' => "return MODX_CORE_PATH . 'components/';",
));
$vehicle->resolve('php',array(
    'source' => $sources['resolvers'] . 'resolve.paths.php',
));
$builder->putVehicle($vehicle);

/* now pack in the license file, readme and setup options */
$builder->setPackageAttributes(array(
    'license' => file_get_contents($sources['docs'] . 'license.txt'),
    'readme' => file_get_contents($sources['docs'] . 'readme.txt'),
    'changelog' => file_get_contents($sources['docs'] . 'changelog.txt'),
));
$modx->log(modX::LOG_LEVEL_INFO, 'Packaged in package attributes.'); flush();

$modx->log(modX::LOG_LEVEL_INFO, 'Packing...'); flush();
$builder->pack();

$mtime= microtime();
$mtime= explode(" ", $mtime);
$mtime= $mtime[1] + $mtime[0];
$tend= $mtime;
$totalTime= ($tend - $tstart);
$totalTime= sprintf("%2.4f s", $totalTime);

$modx->log(modX::LOG_LEVEL_INFO,"\n<br />Package Built.<br />\nExecution time: {$totalTime}\n");

copy(MODX_CORE_PATH . 'packages/' . PKG_NAME_LOWER . '-' . PKG_VERSION . '-' . PKG_RELEASE . '.transport.zip', MODX_BASE_PATH . PKG_NAME_LOWER . '-' . PKG_VERSION . '-' . PKG_RELEASE . '.transport.zip');
exit ();