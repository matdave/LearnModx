<?php
/**
* @package smartcache
* @subpackage processors
*/
class SmartCacheRemoveProcessor extends modObjectRemoveProcessor {
    public $classKey = 'modSmartcache';
    public $languageTopics = array('smartcache:default');
    public $objectType = 'smartcache.smartcache';
}
return 'SmartCacheRemoveProcessor';