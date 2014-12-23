<?php
/**
 * @package learnmodx
 * @subpackage controllers
 */
 
echo get_included_files();
debug_print_backtrace();
require_once dirname(__FILE__) . '/model/learnmodx/learnmodx.class.php';

class IndexManagerController extends modExtraManagerController {
    public static function getDefaultController() {
        return 'home'; 
    }
}
abstract class LearnModxManagerController extends modManagerController {
    public $learnmodx;
    
    public function initialize() {
        $this->learnmodx = new LearnModx($this->modx);

        $this->addJavascript($this->learnmodx->config['jsUrl'] . 'mgr/learnmodx.js');
        $this->addHtml('<script type="text/javascript">
        Ext.onReady(function() {
            LearnModx.config = '.$this->modx->toJSON($this->learnmodx->config).';
        });
        </script>');
        return parent::initialize();
    }
    
    public function getLanguageTopics() {
        return array('learnmodx:default');
    }
    
    public function checkPermissions() {
        return true;
    }
    
    public function process(array $scriptProperties = array()) {
        
    }
}