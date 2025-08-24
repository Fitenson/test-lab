<?php

namespace Fitenson\TestLab\commands;

use Yii;
use yii\console\Controller;


class TestlabController extends Controller {
    /**
     *  This command creates new test file
     *  Example: php yii testlab/create-test UserTest
    */
    public function actionCreateTest($name) {
        $path = Yii::getAlias('@app/tests/functional/' . $name . '.php');

        if(file_exists($path)) {
            $this->stdout('Test file already exists. Path: ' . $path);
        }
    }
}
