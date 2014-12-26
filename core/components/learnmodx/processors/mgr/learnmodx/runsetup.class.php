<?php
// Include base class
require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/model/learnmodx/learnmodx.class.php';

// New instance of LearnModx
$learnmodx = new LearnModx($this);

// Get section content
echo $learnmodx->runSetup($_POST['chapter'], $_POST['section']);

// Kill
exit();