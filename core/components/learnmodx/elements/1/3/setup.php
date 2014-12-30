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

        // Remove old resources
        $this->removeResources();

        // Create resource 1
        $resource = $this->modx->newObject('modResource');
        $resource->set('template', null);
        $resource->set('pagetitle', 'Delete me');
        $resource->set('published', true);
        $resource->save();

        // Set start page
        $this->setSiteStart($resource->get('id'));

        $resource = $this->modx->newObject('modResource');
        $resource->set('template', null);
        $resource->set('pagetitle', 'Publish me');
        $resource->set('published', false);
        $resource->save();

        $resource = $this->modx->newObject('modResource');
        $resource->set('template', null);
        $resource->set('pagetitle', 'Hide me from menus');
        $resource->set('published', true);
        $resource->save();

        // Clear cache
        $this->clearCache();
    }
}