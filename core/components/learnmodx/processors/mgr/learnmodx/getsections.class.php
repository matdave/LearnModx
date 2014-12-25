<?php
// Include base class
require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/model/learnmodx/learnmodx.class.php';

// New instance of LearnModx
$learnmodx = new LearnModx($this);

// Get all sections for chapter
$sections = $learnmodx->getSectionsForChapter($_POST['chapter']);

// Output as processor
echo $learnmodx->output($sections);

// Kill
exit();