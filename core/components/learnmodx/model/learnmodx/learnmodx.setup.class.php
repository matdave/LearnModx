<?php

class LearnModxSetup {

    /**
     * Pointer to Modx
     */

    protected $modx;

    /**
     * Constructor
     */

    public function __construct(modX &$modx) {
        $this->modx = &$modx;
    }

    /**
     * Delete all snippets
     */

    protected function removeSnippets() {
        $this->modx->removeCollection('modSnippet');
    }

    /**
     * Clear site cache
     */

    protected function clearCache() {
        $this->modx->runProcessor(array(
            'location' => 'system',
            'action' => 'clearCache',
        ));
    }
}