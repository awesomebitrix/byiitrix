<?php
/**
 * @var CMain $APPLICATION
 */

global $APPLICATION;

$host_parts       = explode('.', $_SERVER['HTTP_HOST']);
$sub_domain_parts = [];
$is_local         = $host_parts[count($host_parts) - 1] === 'in';
$limit            = $is_local ? 3 : 2;

while( count($host_parts) > $limit ) {
    $sub_domain_parts[] = array_shift($host_parts);
}

define('APP_ROOT', dirname(dirname(dirname(__DIR__))));
define('WEB_ROOT', dirname(dirname(__DIR__)));
define('MAIN_HTTP_HOST', implode('.', $host_parts));
define('SUB_DOMAIN', implode('.', $sub_domain_parts));
define('BX_CACHE_SID', md5($_SERVER['HTTP_HOST'] . $_SERVER['DOCUMENT_ROOT']));

require APP_ROOT . '/vendor/autoload.php';
require APP_ROOT . '/common/config/define.php';
require APP_ROOT . '/vendor/yiisoft/yii2/Yii.php';
require APP_ROOT . '/common/config/bootstrap.php';

\CModule::IncludeModule('iblock');

if( defined('YII_CUSTOM_APP') === false ) {
    \AddEventHandler('main', 'OnBeforeProlog', function () {
        $app = new \common\bitrix\Application(require APP_ROOT . '/common/config/main.php');

        $app->run();
    });
}
