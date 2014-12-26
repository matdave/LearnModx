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

        // Get resouce
        $c = $this->modx->newQuery('modResource');
        $c->where(array('pagetitle' => 'LearnModx'));
        $resource = $this->modx->getObject('modResource', $c);
        $resource_longtitle = null;
        if ($resource != null) {
            $resource_longtitle = $resource->get('longtitle');
        }
        $this->compare('Hello', $resource_longtitle);

        // Get template
        $c2 = $this->modx->newQuery('modTemplate');
        $c2->where(array('templatename' => 'LearnModx'));
        $template = $this->modx->getObject('modTemplate', $c2);
        $template_content = null;
        if ($template != null) {
            $template_content = $template->get('content');
        }
        $this->compare('[[*longtitle]]', $template_content);
    }
}