<?php
// Include base class
require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/model/learnmodx/learnmodx.class.php';

// New instance of LearnModx
$learnmodx = new LearnModx($this);

// Get all chapters
$chapters = $learnmodx->getAllChapters();

// Output as processor
echo $learnmodx->output($chapters);

// Kill
exit();