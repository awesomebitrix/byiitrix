<?php
/**
 * Main console configuration file
 */

return \yii\helpers\ArrayHelper::merge(
    require APP_DIR . '/common/config/main.php',
    [
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
    ],
    require __DIR__ . '/main.local.php'
);
