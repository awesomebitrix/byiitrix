<?php

use yii\swiftmailer\Mailer;
use yii\caching\MemCache;
use yii\caching\FileCache;
use common\bitrix\ServiceLocator;

/**
 * Main common configuration file
 */

global $DB;

$parts = explode(':', $DB->DBHost);
$host  = $parts[0];
$port  = (isset($parts[1]) && is_numeric($parts[1])) ? (int)$parts[1] : 3306;

return \yii\helpers\ArrayHelper::merge([
    'id'          => 'byiitrix',
    'basePath'    => dirname(__DIR__),
    'components'  => [
        'bitrix' => [
            'class' => ServiceLocator::class,
        ],
        'cache'  => [
            'class' => FileCache::class,
            //'class'     => MemCache::class,
            //'keyPrefix' => 'byiitrix:',
        ],
        'db'     => [
            'class'               => 'yii\db\Connection',
            'dsn'                 => "mysql:host={$host};port={$port};dbname={$DB->DBName}",
            'username'            => $DB->DBLogin,
            'password'            => $DB->DBPassword,
            'charset'             => 'utf8',
            'enableSchemaCache'   => true,
            'schemaCacheDuration' => 3600,
            'schemaCache'         => 'cache',
        ],
        'mailer' => [
            'class'    => Mailer::class,
            'viewPath' => '@common/mail',
        ],
    ],
    'language'    => 'ru',
    'name'        => 'Bitrix via yii2',
    'params'      => require __DIR__ . '/params.php',
    'vendorPath'  => APP_ROOT . '/vendor',
    'runtimePath' => '@common/runtime',
], file_exists(__DIR__ . '/main.local.php') ? require __DIR__ . '/main.local.php' : []);
