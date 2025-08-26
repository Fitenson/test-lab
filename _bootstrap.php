<?php
// tests/functional/_bootstrap.php

defined('YII_ENV') or define('YII_ENV', 'test');
defined('YII_DEBUG') or define('YII_DEBUG', true);

require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../../../vendor/yiisoft/yii2/Yii.php';

// Reuse your development DB config
$config = yii\helpers\ArrayHelper::merge(
    require __DIR__ . '/../../../common/config/main.php',
    require __DIR__ . '/../../../common/config/main-local.php',
    require __DIR__ . '/../../../common/config/test.php',
);

new yii\web\Application($config);
