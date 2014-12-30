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

        // Get resource
        $c = $this->modx->newQuery('modResource');
        $c->where(array('pagetitle' => 'Hello'));
        $resource = $this->modx->getObject('modResource', $c);
        $resource_longtitle = null;
        $resource_content = null;
        if ($resource != null) {
            $resource_longtitle = $resource->get('longtitle');
            $resource_content = $resource->get('content');
        }
        $this->compare('Hello world', $resource_longtitle);
        $this->compare('Lorem ipsum', $resource_content);
    }
}