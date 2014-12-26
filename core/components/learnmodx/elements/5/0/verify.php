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
        $resource = $this->getResource(1);

        if ($resource != null) {
            $this->compare('Hello', $resource->get('pagetitle'));
        }
    }
}