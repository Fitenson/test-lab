<?php

namespace Fitenson\TestLab\core;

use FunctionalTester;


abstract class BaseCest {
    public function __before(FunctionalTester $tester) {
        $tester->amOnPage();
    }
}
