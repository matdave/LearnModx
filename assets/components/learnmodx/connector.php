<?php
/**
 * Learnmodx Connector
 *
 * @package learmodx
 */
require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/config.core.php';
require_once MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';
require_once MODX_CONNECTORS_PATH . 'index.php';

$namespace = $modx->getObject('modNamespace','learnmodx');
$corePath = $namespace->get('path');

require_once $corePath . 'model/learnmodx/learnmodx.class.php';
$modx->learnmodx = new LearnModx($modx);

$modx->lexicon->load('learnmodx:default');

/* handle request */
$path = $modx->getOption('processorsPath', $modx->learnmodx->config, $corePath.'processors/');
$modx->request->handleRequest(array(
    'processors_path' => $path,
    'location' => '',
));