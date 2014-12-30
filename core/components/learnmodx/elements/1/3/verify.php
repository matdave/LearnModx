<?php
require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/model/learnmodx/learnmodx.verify.class.php';

/**
 * Setup for this section
 */

class SectionVerify extends LearnModxVerify {

    /**
     * Constructor
     */

    public function __construct(modX &$modx) {
        parent::__construct($modx);

        // Deleted
        $c = $this->modx->newQuery('modResource');
        $c->where(array('pagetitle' => 'Delete me'));
        $resource = $this->modx->getObject('modResource', $c);
        $resource_deleted = null;
        if ($resource != null) {
            $resource_deleted = $resource->get('deleted');
        }

        // Published
        $c = $this->modx->newQuery('modResource');
        $c->where(array('pagetitle' => 'Publish me'));
        $resource = $this->modx->getObject('modResource', $c);
        $resource_published = null;
        if ($resource != null) {
            $resource_published = $resource->get('published');
        }

        // Hidden from menus
        $c = $this->modx->newQuery('modResource');
        $c->where(array('pagetitle' => 'Hide me from menus'));
        $resource = $this->modx->getObject('modResource', $c);
        $resource_hidden = null;
        if ($resource != null) {
            $resource_hidden = $resource->get('hidemenu');
        }

        // Compare!
        $this->compare(true, $resource_deleted);
        $this->compare(true, $resource_published);
        $this->compare(true, $resource_hidden);
    }
}