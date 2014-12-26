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

        // Remove stuff
        $this->removeTemplates();
        $this->removeResources();

        // Create template
        $template = $this->modx->newObject('modTemplate');
        $template->set('templatename', 'LearnModx');
        $template->save();

        // Create
        $resource = $this->modx->newObject('modResource');
        $resource->set('template', $template->get('id'));
        $resource->set('pagetitle', 'LearnModx');
        $resource->set('published', true);
        $resource->save();

        // Set start page
        $this->setSiteStart($resource->get('id'));

        // Clear cache
        $this->clearCache();
    }
}