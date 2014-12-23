<?php
require_once dirname(dirname(__FILE__)) . '/index.class.php';
/**
 * Encapsulates the interaction of MODx manager with an HTTP request.
 *
 * {@inheritdoc}
 *
 * @package learnmodx
 * @extends LearnModx
 */
class LearnModxControllerRequest extends LearnModxBaseManagerController  {
    public $discuss = null;
    public $actionVar = 'action';
    public $defaultAction = 'home';

    function __construct(LearnModx &$learnmodx) {
        parent::__construct($learnmodx->modx);
        $this->learnmodx = &$learnmodx;
    }

    /**
     * Extends modRequest::handleRequest and loads the proper error handler and
     * actionVar value.
     *
     * {@inheritdoc}
     */
    public function handleRequest() {
        $this->loadErrorHandler();

        /* save page to manager object. allow custom actionVar choice for extending classes. */
        $this->action = isset($_REQUEST[$this->actionVar]) ? $_REQUEST[$this->actionVar] : $this->defaultAction;

        $modx =& $this->modx;
        $learnmodx =& $this->learnmodx;
        $viewHeader = include $this->learnmodx->config['corePath'] . 'controllers/mgr/header.php';
        
        $f = $this->learnmodx->config['corePath'].'controllers/mgr/' . $this->action . '.php';
        if (file_exists($f)) {
            $viewOutput = include $f;
        }
        else {
            $viewOutput = 'Controller not found: ' . $f;
        }

        return $viewHeader . $viewOutput;
    }
}