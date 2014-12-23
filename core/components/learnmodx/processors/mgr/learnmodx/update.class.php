<?php
/**
* @package modSmartcache
* @subpackage processors
*/

class SmartCacheUpdateProcessor extends modObjectUpdateProcessor {
    public $classKey = 'modSmartcache';
    public $languageTopics = array('modsmartcache:default');
    public $objectType = 'modsmartcache.modsmartcache';
    
    public function beforeSet() {
        foreach (array('children','on_create','on_update','on_sort','on_delete') as $k) {
            if ($this->getProperty($k)) {
                $this->setProperty($k,true);
            }
            else {
                $this->setProperty($k,false);
            }
        }
        
        return parent::beforeSet();
    }
}
return 'SmartCacheUpdateProcessor';

?>