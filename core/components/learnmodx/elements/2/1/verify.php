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

        // Get resouce 1
        $c = $this->modx->newQuery('modResource');
        $c->where(array('pagetitle' => 'Test1'));
        $resource = $this->modx->getObject('modResource', $c);
        $output = null;
        if ($resource != null) {
            $output = $this->loadContent($resource->get('id'));
        }
        $this->compare('Test1', $output);

        unset($c, $resource, $output);

        // Get resouce 2
        $c = $this->modx->newQuery('modResource');
        $c->where(array('pagetitle' => 'Test2'));
        $resource = $this->modx->getObject('modResource', $c);
        $output = null;
        if ($resource != null) {
            $output = $this->loadContent($resource->get('id'));
        }
        $this->compare('Test2 long', $output);
    }
}