<?php
/**
 * Main console configuration file
 */

$local = str_replace('.php', '.local.php', __FILE__);

return \yii\helpers\ArrayHelper::merge(require APP_ROOT . '/common/config/main.php', [
    'id'                  => 'app-console',
    'basePath'            => dirname(__DIR__),
    'bootstrap'           => ['log'],
    'controllerNamespace' => 'console\controllers',
    'components'          => [
        'log' => [
            'targets' => [
                [
                    'class'  => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
    ],
], file_exists(__DIR__ . '/main.local.php') ? require __DIR__ . '/main.local.php' : []);
