<?php
require_once dirname(dirname(__FILE__)) . '/chapter.interface.php';

class Chapter0 implements ChapterInterface {
    public static $chapter = 1;
    public static $name = 'Intro';

    public static function getSections() {
        return array(array('id' => 0, 'section' => 'Section01'), array('id' => 1, 'section' => 'Section12'));
    }
}