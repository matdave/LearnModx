<?php
require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/model/learnmodx/learnmodx.setup.class.php';

/**
 * Setup for this section
 */

class SectionSetup extends LearnModxSetup {

    /**
     * Constructor
     */

    public function __construct(modX &$modx) {
        parent::__construct($modx);

        // Delete all snippets
        $this->removeSnippets();

        // Clear cache
        $this->clearCache();
    }
}