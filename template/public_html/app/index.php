<?php

define('YII_CUSTOM_APP', true);

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';

require_once dirname(realpath($_SERVER['DOCUMENT_ROOT'])) . '/common/config/define.php';

require_once APP_ROOT . '/vendor/autoload.php';
require_once APP_ROOT . '/vendor/yiisoft/yii2/Yii.php';
require_once APP_ROOT . '/common/config/bootstrap.php';

$config = require APP_ROOT . '/frontend/config/main.php';

$application = new \common\web\Application($config);
$application->run();

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_after.php';
