<?php
/**
 * @package learnmodx
 * @subpackage controllers
 */
 
class LearnModxHomeManagerController extends LearnModxManagerController {
    public function getPageTitle() {
        return $this->modx->lexicon('learnmodx');
    }
    
    public function loadCustomCssJs() {
        $this->addJavascript($this->learnmodx->config['jsUrl'] . 'mgr/widgets/learnmodx.grid.js');
        $this->addJavascript($this->learnmodx->config['jsUrl'] . 'mgr/widgets/home.panel.js');
        $this->addLastJavascript($this->learnmodx->config['jsUrl'] . 'mgr/sections/index.js');

        $this->addCSS($this->learnmodx->config['cssUrl'] . 'learnmodx.css');
    }
    
    public function getTemplateFile() {
        return $this->learnmodx->config['templatesPath'] . 'home.tpl';
    }
}