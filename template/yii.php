<?php

define('BX_UTF', true);
define('NO_KEEP_STATISTIC', true);
define('NOT_CHECK_PERMISSIONS', true);
define('BX_BUFFER_USED', true);

$_SERVER['DOCUMENT_ROOT'] = __DIR__ . '/public_html';

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';

require_once __DIR__ . '/common/config/define.php';
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/vendor/yiisoft/yii2/Yii.php';
require_once __DIR__ . '/common/config/bootstrap.php';

$config      = require __DIR__ . '/console/config/main.php';
$application = new \common\console\Application($config);
$exitCode    = $application->run();

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_after.php';

exit($exitCode);
