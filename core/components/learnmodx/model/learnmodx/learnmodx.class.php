<?php
/**
 *
 * Copyright 2014 by Thomas Gautvedt <thomas[at]gautvedt[dot]no>
 *
 * LearnModx is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * LearnModx is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * LearnModx; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package learnmodx
 */

class LearnModx {
    
    /**
     * Variables
     */

    private $modx;
    private $elementsPath;
    private $chapter;
    private $section;
    private $chapters;
    
    /**
     * Constructs the LearnModx object
     *
     * @param modX &$modx A reference to the modX object
     * @param array $config An array of configuration options
     */
    function __construct(modX &$modx, array $config = array()) {
        $this->modx = &$modx;

        // Load chapter and section here
        $this->chapter = 0;
        $this->section = 0;

        // Usual stuff
        $basePath = $this->modx->getOption('learnmodx.core_path', $config,$this->modx->getOption('core_path') . 'components/learnmodx/');
        $assetsUrl = $this->modx->getOption('learnmodx.assets_url', $config,$this->modx->getOption('assets_url') . 'components/learnmodx/');

        $this->elementsPath = $basePath . 'elements/';

        $this->config = array_merge(array(
            'basePath' => $basePath,
            'corePath' => $basePath,
            'modelPath' => $basePath . 'model/',
            'processorsPath' => $basePath . 'processors/',
            'templatesPath' => $basePath . 'templates/',
            'chunksPath' => $basePath . 'elements/chunks/',
            'jsUrl' => $assetsUrl . 'js/',
            'cssUrl' => $assetsUrl . 'css/',
            'assetsUrl' => $assetsUrl,
            'connectorUrl' => $assetsUrl . 'connector.php',

            // LearnModx stuff
            'elementsPath' => $this->elementsPath,
            'chapter' => $this->chapter,
            'section' => $this->section,
        ), $config);


        $this->modx->addPackage('learnmodx', $this->config['modelPath']);

        // Load all chapters
        $this->getAllChapters();
    }

    /**
     * Initializes the class into the proper context
     *
     * @access public
     * @param string $ctx
     */
    public function initialize($ctx = 'web') {
        switch ($ctx) {
            case 'mgr':
                $this->modx->lexicon->load('learnmodx:default');

                if (!$this->modx->loadClass('learnmodx.request.LearnModxControllerRequest', $this->config['modelPath'] . 'learnmodx/request/', true, true)) {
                    return 'Could not load controller request handler.';
                }
                $this->request = new LearnModxControllerRequest($this);
                return $this->request->handleRequest();
            break;
        }
        return true;
    }

    /**
     * Getters for chapters and sections
     */

    public function getChapter() {
        return $this->chapter;
    }
    public function getSection() {
        return $this->section;
    }

    /**
     * Return all chapters
     */

    public function getAllChapters() {
        if ($this->chapters == null) {
            // Hold all chapters
            $this->chapters = array();

            // Get all chapters
            $dirs = glob($this->elementsPath . '*' , GLOB_ONLYDIR);

            // Loop all chapters
            foreach ($dirs as $dir) {
                // Get chapter id
                $dir_split = explode('/', $dir);
                $num = $dir_split[count($dir_split) - 1];

                // Include class
                require_once $dir . '/chapter' . $num . '.php';

                // Get variables from class
                $vars = get_class_vars('Chapter' . $num);

                // Get information about class
                $this->chapters[] = array(
                    'id' => $num,
                    'chapter' => $vars['chapter'] . ' - ' . $vars['name'],
                );
            }
        }


        // Return chapters
        return $this->chapters;
    }

    /**
     * Return section for a given chapter
     */

    public function getSectionsForChapter($chapter) {
        return call_user_func_array(array('Chapter' . $this->chapter, 'getSections'), array());
    }

    /**
     * Returns name for a given chapter
     */

    public function getChapterById($chapter_id) {
        $chapters = $this->getAllChapters();
        foreach ($chapters as $chapter) {
            if ($chapter['id'] == $chapter_id) {
                return $chapter['chapter'];
            }
        }

        return '';
    }

    /**
     * Returns name for a given section
     */

    public function getSectionById($chapter, $section) {
        //
    }

    /**
     * Output for processors
     */

    public function output($arr) {
        return json_encode(array(
            'success' => true,
            'results' => $arr,
            'total' => count($arr),
        ));
    }
}