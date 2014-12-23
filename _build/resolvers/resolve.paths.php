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

if ($object->xpdo) {
    // Reference to modx
    $modx =& $object->xpdo;
    
    // Create directories if they don't exist
    @mkdir($modx->getOption('assets_path') . 'components/', 0775);
    @mkdir($modx->getOption('assets_path') . 'components/staticcollector/', 0775);
    @mkdir($modx->getOption('assets_path') . 'components/staticcollector/elements', 0775);
    @mkdir($modx->getOption('assets_path') . 'components/staticcollector/elements/snippets/', 0775);
    @mkdir($modx->getOption('assets_path') . 'components/staticcollector/elements/templates/', 0775);
    @mkdir($modx->getOption('assets_path') . 'components/staticcollector/elements/chunks/', 0775);
    @mkdir($modx->getOption('assets_path') . 'components/staticcollector/settings/', 0775);
    
    // Create files
    @file_put_contents($modx->getOption('assets_path') . 'components/staticcollector/settings/checksums.json', '[]');
    @file_put_contents($modx->getOption('assets_path') . 'components/staticcollector/settings/history.log', '');
}

// Because...
return true;