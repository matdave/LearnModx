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

require_once dirname(dirname(dirname(__FILE__))) . '/vendor/michelf/php-markdown/Michelf/MarkdownExtra.inc.php';

class LearnModx {
    
    /**
     * Variables
     */

    private $modx;
    private $elementsPath;
    private $currentChapter;
    private $crrentSection;

    /**
     * Chapters
     */

    private $chapters = array(
        array(
            'id' => 0,
            'name' => '1 - Intro'
        ),
        array(
            'id' => 1,
            'name' => '2 - Elements'
        ),
        array(
            'id' => 2,
            'name' => '2 - Test123'
        ),
    );

    /**
     * Sections
     */

    private $sections = array(
        0 => array(
            0 => array(
                'id' => 0,
                'name' => 'Hello World',
            ),
            1 => array(
                'id' => 1,
                'name' => 'Hello world again',
            ),
        ),
        1 => array(
            0 => array(
                'id' => 0,
                'name' => 'Hello2',
            ),
            1 => array(
                'id' => 1,
                'name' => 'Hello2-2',
            ),
        ),
        2 => array(
            0 => array(
                'id' => 0,
                'name' => 'Hello2',
            ),
            1 => array(
                'id' => 1,
                'name' => 'Hello2-2',
            ),
        ),
    );
    
    /**
     * Constructs the LearnModx object
     *
     * @param modX &$modx A reference to the modX object
     * @param array $config An array of configuration options
     */
    function __construct(modX &$modx, array $config = array()) {
        $this->modx = &$modx;

        // Usual stuff
        $basePath = $this->modx->getOption('learnmodx.core_path', $config,$this->modx->getOption('core_path') . 'components/learnmodx/');
        $assetsUrl = $this->modx->getOption('learnmodx.assets_url', $config,$this->modx->getOption('assets_url') . 'components/learnmodx/');

        // Paths for LearnModx
        $this->elementsPath = $basePath . 'elements/';
        $this->storeagePath = $basePath . 'storeage/';

        // Load progress
        $this->loadProgress();

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
            'chapter' => $this->currentChapter,
            'section' => $this->currentSection,
        ), $config);

        $this->modx->addPackage('learnmodx', $this->config['modelPath']);
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
     * Load progress from json file
     */

    private function loadProgress() {
        // Check if progress is stores
        if (!file_exists($this->storeagePath . '/progress.json')) {
            // Not saved, start from scratch
            $progress = array(
                'chapter' => 0,
                'section' => 0,
            );
        }
        else {
            // Might be saved, fetch data and try to parse
            $progress = array();
            $progress_json = json_decode(file_get_contents($this->storeagePath . '/progress.json'), true);

            // Check chapter
            if (isset($progress_json['chapter'])) {
                $progress['chapter'] = $progress_json['chapter'];
            }
            else {
                $progress['chapter'] = 0;
            }

            // Check section
            if (isset($progress_json['section'])) {
                $progress['section'] = $progress_json['section'];
            }
            else {
                $progress['section'] = 0;
            }
        }

        $this->saveProgress($progress);
    }

    /**
     * Save progress
     */

    public function saveProgress($arr) {
        // Store in class
        $this->currentChapter = $arr['chapter'];
        $this->currentSection = $arr['section'];

        // Save in file
        file_put_contents($this->storeagePath . '/progress.json', json_encode($arr));
    }

    /**
     * Getters for chapters and sections
     */

    public function getChapter() {
        return $this->thisChapter;
    }
    public function getSection() {
        return $this->currentSection;
    }

    /**
     * Return all chapters
     */

    public function getAllChapters() {
        // Return chapters
        return $this->chapters;
    }

    /**
     * Return section for a given chapter
     */

    public function getSectionsForChapter($chapter) {
        // Store progress
        $this->saveProgress(array('chapter' => $chapter,
            'section' => 0));

        // Return
        return $this->sections[$chapter];
    }

    /**
     * Returns content for a section
     */

    public function getSectionContent($chapter, $section) {
        // Store progress
        $this->saveProgress(array('chapter' => $chapter,
            'section' => $section));

        // Get content
        $content = file_get_contents($this->config['elementsPath'] . $this->currentChapter . '/section' . $this->currentSection . '.md');

        // New instance of Markdown
        $parser = new \Michelf\MarkdownExtra;

        // Parse
        return $parser::defaultTransform($content);
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
    public function rawOutput($content) {
        return json_encode(array(
            'success' => true,
            'content' => $content
        ));
    }
}