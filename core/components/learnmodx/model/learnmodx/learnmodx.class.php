<?php
/**
 *
 * Copyright 2014 by Thomas Gautvedt <thomas[at]gautvedt[dot]no>
 *
 * LearnModx is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * LearnModx is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * LearnModx; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package learnmodx
 */

class LearnModx {
    
    /**
     * Variables
     */
    
    private $modx;
    
    /**
     * Constructs the LearnModx object
     *
     * @param modX &$modx A reference to the modX object
     * @param array $config An array of configuration options
     */
    function __construct(modX &$modx, array $config = array()) {
        $this->modx = &$modx;

        $basePath = $this->modx->getOption('learnmodx.core_path', $config,$this->modx->getOption('core_path') . 'components/learnmodx/');
        $assetsUrl = $this->modx->getOption('learnmodx.assets_url', $config,$this->modx->getOption('assets_url') . 'components/learnmodx/');
        $this->config = array_merge(array(
            'basePath' => $basePath,
            'corePath' => $basePath,
            'modelPath' => $basePath . 'model/',
            'processorsPath' => $basePath . 'processors/',
            'templatesPath' => $basePath . 'templates/',
            'chunksPath' => $basePath . 'elements/chunks/',
            'jsUrl' => $assetsUrl . 'js/',
            'cssUrl' => $assetsUrl . 'css/',
            'assetsUrl' => $assetsUrl,
            'connectorUrl' => $assetsUrl . 'connector.php',
        ), $config);

        $this->modx->addPackage('learnmodx', $this->config['modelPath']);
    }

    /**
     * Initializes the class into the proper context
     *
     * @access public
     * @param string $ctx
     */
    public function initialize($ctx = 'web') {
        switch ($ctx) {
            case 'mgr':
                $this->modx->lexicon->load('learnmodx:default');

                if (!$this->modx->loadClass('learnmodx.request.LearnModxControllerRequest', $this->config['modelPath'] . 'learnmodx/request/', true, true)) {
                    return 'Could not load controller request handler.';
                }
                $this->request = new LearnModxControllerRequest($this);
                return $this->request->handleRequest();
            break;
        }
        return true;
    }
}