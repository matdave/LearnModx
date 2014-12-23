<?php
/**
* Get a list of Resources
*
* @package modSmartcache
* @subpackage processors
*/
class ResourceGetListProcessor extends modObjectGetListProcessor {
    public $classKey = 'modResource';
    public $languageTopics = array('dates:default');
    public $defaultSortField = 'pagetitle';
    public $defaultSortDirection = 'ASC';
    public $objectType = 'dates.date';
    
    public function initialize() {
        
        $this->setDefaultProperties(array(
            'start' => 0,
            'limit' => 0,
            'sort' => $this->defaultSortField,
            'dir' => $this->defaultSortDirection,
            'combo' => false,
            'query' => '',
        ));
        return true;
    }
    
    public function prepareQueryBeforeCount(xPDOQuery $c) {
        $c->select(array(
            'id',
            'pagetitle'
        ));
        $parent = $this->getProperty('parent');
		$this->setProperty('limit', 0);
        if (!empty($parent)) {
            $c->where(array(
                'parent' => $parent
            ));
        }
        return $c;
    }
    
    public function prepareRow(xPDOObject $object) {
        $row =  $object->toArray('', false, true, true);
        return $row;
    }
}
return 'ResourceGetListProcessor';