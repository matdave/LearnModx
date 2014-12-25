<?php
require_once dirname(dirname(__FILE__)) . '/chapter.interface.php';

class Chapter1 implements ChapterInterface {
    public static $chapter = 2;
    public static $name = 'Elements';

    public static function getSections() {
        return array(array('id' => 0, 'section' => 'Section21'), array('id' => 1, 'section' => 'Section22'));
    }
}