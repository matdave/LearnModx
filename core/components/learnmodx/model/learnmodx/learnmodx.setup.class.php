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

    protected function removeTemplates() {
        $this->modx->removeCollection('modTemplate');
    }

    protected function removeResources() {
        $this->modx->removeCollection('modResource');
    }

    protected function setSiteStart($id) {
        $Setting = $this->modx->getObject('modSystemSetting', 'site_start');
        $Setting->set('value', $id);
        $Setting->save();
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