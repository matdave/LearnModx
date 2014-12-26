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

    // Paths
    private $basePath;
    private $elementsPath;
    private $storeagePath;

    // Progress
    private $currentChapter;
    private $currentSection;
    
    /**
     * Constructs the LearnModx object
     *
     * @param modX &$modx A reference to the modX object
     * @param array $config An array of configuration options
     */
    function __construct(modX &$modx, array $config = array()) {
        $this->modx = &$modx;

        // Get namespace core path
        $namespace = $this->modx->getObject('modNamespace','learnmodx');
        $basePath = $namespace->get('path');

        // Usual stuff
        $assetsUrl = $this->modx->getOption('learnmodx.assets_url', $config,$this->modx->getOption('assets_url') . 'components/learnmodx/');

        // Paths for LearnModx
        $this->basePath = $basePath;
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
            $chapter = 0;
            $section = 0;
        }
        else {
            // Might be saved, fetch data and try to parse
            $progress = array();
            $progress_json = json_decode(file_get_contents($this->storeagePath . '/progress.json'), true);

            // Check chapter
            if (isset($progress_json['chapter'])) {
                $chapter = $progress_json['chapter'];
            }
            else {
                $chapter = 0;
            }

            // Check section
            if (isset($progress_json['section'])) {
                $section = $progress_json['section'];
            }
            else {
                $section = 0;
            }
        }

        $this->saveProgress($chapter, $section);
    }

    /**
     * Save progress
     */

    public function saveProgress($chapter, $section) {
        // Store in class
        $this->currentChapter = $chapter;
        $this->currentSection = $section;

        // Save in file
        file_put_contents($this->storeagePath . '/progress.json',
            json_encode(array(
                'chapter' => $chapter,
                'section' => $section)));
    }

    /**
     * Return all chapters
     */

    public function getAllChapters() {
        $chapters = array();

        // Read lmx file
        $handle = fopen($this->elementsPath . 'chapters.lmx', 'r');
        if ($handle) {
            $id = 0;
            while (($line = fgets($handle)) !== false) {
                $chapters[] = array(
                    'id' => $id,
                    'name' => ($id + 1) . ' - ' . str_replace("\n", '', $line),
                );

                $id++;
            }
        }

        // Close file
        fclose($handle);

        // Return chapters
        return $chapters;
    }

    /**
     * Return section for a given chapter
     */

    public function getSectionsForChapter($chapter) {
        // Save progress
        $this->saveProgress($chapter, 0);

        $sections = array();

        // Get sections
        $id = 0;
        foreach(glob($this->elementsPath . $chapter . '/*', GLOB_ONLYDIR) as $dir) {
            $content = file_get_contents($dir . '/name.lmx');
            $sections[] = array(
                'id' => $id,
                'name' => ($id + 1) . ' - ' . str_replace("\n", '', $content),
            );

            $id++;
        }

        // Return sections
        return $sections;
    }

    /**
     * Returns content for a section
     */

    public function getSectionContent($chapter, $section) {
        // Store progress
        $this->saveProgress($chapter, $section);

        // Get content
        $content = file_get_contents($this->config['elementsPath'] . $this->currentChapter . '/' . $this->currentSection . '/description.md');

        // New instance of Markdown
        $parser = new \Michelf\MarkdownExtra;

        // Parse
        return $parser::defaultTransform($content);
    }

    /**
     * Run setup for a section
     */

    public function runSetup($chapter, $section) {
        $content = '';
        $success = true;
        $msg = null;

        // Check if there are any setup file for this section
        if (file_exists($this->config['elementsPath'] . $chapter . '/' . $section . '/setup.php')) {
            // Include file
            require_once $this->config['elementsPath'] . $chapter . '/' . $section . '/setup.php';

            // Run setup
            $class =new ReflectionClass('SectionSetup');
            $setup = $class->newInstanceArgs(array($this->modx));
        }
        else {
            $success = false;
            $msg = 'This section does not have any setup. Nothing was altered.';
        }

        // Return
        return $this->rawOutput($content, $success, $msg);
    }

    /**
     * Run verify for a section
     */

    public function runVerify($chapter, $section) {
        $content = '';
        $msg = null;

        // Check if there are any setup file for this section
        if (file_exists($this->config['elementsPath'] . $chapter . '/' . $section . '/verify.php')) {
            // Include file
            require_once $this->config['elementsPath'] . $chapter . '/' . $section . '/verify.php';

            // Run setup
            $class = new ReflectionClass('SectionVerify');
            $verify = $class->newInstanceArgs(array($this->modx));

            $msg = $verify->cleanOutput();
        }
        else {
            $content = 'Error';
            $msg = 'This section can\'t be verified. Nothing was done.';
        }

        if ($content == '') {
            if ($verify->getState()) {
                $content = 'Hurray!';
            }
            else {
                $content = 'Darn';
            }
        }

        // Return
        return $this->rawOutput($content, false, $msg);
    }

    /**
     * Output for processors
     */

    public function output($arr, $success = true) {
        return json_encode(array(
            'success' => $success,
            'results' => $arr,
            'total' => count($arr),
        ));
    }
    public function rawOutput($content, $success = true, $message = null) {
        // Initial array
        $output = array(
            'success' => $success,
            'content' => $content,
        );

        // Add message if supplied
        if ($message !== null) {
            $output['message'] = $message;
        }

        // Return
        return json_encode($output);
    }
}