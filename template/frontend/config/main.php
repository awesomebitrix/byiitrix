<?php
/**
 * Main frontend configuration file
 */

return \yii\helpers\ArrayHelper::merge(require APP_DIR . '/common/config/main.php', [
    'id'                  => 'app-frontend',
    'basePath'            => dirname(__DIR__),
    'bootstrap'           => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components'          => [
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'log'          => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets'    => [
                [
                    'class'  => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'response'     => [
            'class' => 'common\web\Response',
        ],
        'request'      => [
            'class'   => 'common\web\Request',
            'baseUrl' => '/app',
        ],
        'view'         => [
            'class' => 'common\web\View',
        ],
        'urlManager'   => [
            'enablePrettyUrl' => true,
            'showScriptName'  => false,
            'rules'           => require __DIR__ . '/rules.php',
        ],
        'user'         => [
            'class'           => 'common\web\User',
            'identityClass'   => 'common\models\User',
            'loginUrl'        => ['/auth/login'],
            'enableAutoLogin' => true,
        ],
    ],
], require __DIR__ . '/main.local.php');
