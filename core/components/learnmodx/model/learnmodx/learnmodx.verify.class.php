<?php

require_once dirname(dirname(dirname(__FILE__))) . '/vendor/autoload.php';

use SebastianBergmann\Comparator\Factory;
use SebastianBergmann\Comparator\ComparisonFailure;

class LearnModxVerify {

    /**
     * Pointer to Modx
     */

    protected $modx;
    protected $factory;
    protected $compares;
    protected $state;

    /**
     * Constructor
     */

    public function __construct(modX &$modx) {
        $this->modx = &$modx;
        $this->factory = new Factory;
        $this->state = true;
        $this->compares = array();
    }

    protected function getResource($id) {
        return $this->modx->getObject('modResource', $id);
    }

    protected function compare($expected, $actual) {
        $res = array(
            'expected' => $expected,
            'actual' => $actual,
            'results' => true,
        );
        $comparator = $this->factory->getComparatorFor($expected, $actual);

        try {
            $comparator->assertEquals($expected, $actual);
        }
        catch (ComparisonFailure $failure) {
            $this->state = false;
            $res['results'] = false;
        }

        $this->compares[] = $res;
    }

    public function getState() {
        return $this->state;
    }

    public function cleanOutput() {
        $str = '<ol style="list-style: inside inside none;">';

        $good = 0;
        $bad = 0;

        // List each test
        foreach ($this->compares as $compare) {
            if ($compare['results']) {
                $good++;
                $color = 'green';
            }
            else {
                $bad++;
                $color = 'red';
            }
            $str .= '<li style="color: ' . $color . '">Expected \'' . $compare['expected'] . '\'. Got \'' . $compare['actual'] . '\'.</li>';
        }

        // Space
        $str .= '</ol><p>&nbsp;</p>';

        // Info
        $str .= '<p>Ran ' . count($this->compares) . ' tests. ' . $good . ' passed, ' . $bad . ' failed.</p>';

        // Space
        $str .= '<p><hr /></p>';

        // Vertict
        if ($this->state) {
            $str .= '<p>Section completed!</p>';
        }
        else {
            $str .= '<p>Section not completed!</p>';
        }

        return $str;
    }

    public function loadContent($id) {
        $ch = curl_init();

        curl_setopt_array($ch, array(
            CURLOPT_URL => $this->modx->getOption('site_url') . $this->modx->makeUrl($id),
            CURLOPT_RETURNTRANSFER => true
        ));

        return curl_exec($ch);
    }
}