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
            'baseUrl' => '/yii',
        ],
        'view'         => [
            'class' => 'byiitrix\web\View',
        ],
        'urlManager'   => [
            'enablePrettyUrl' => true,
            'showScriptName'  => false,
            'rules'           => require __DIR__ . '/rules.php',
        ],
        'user'         => [
            'class'           => 'byiitrix\web\WebUser',
            'identityClass'   => 'byiitrix\web\User',
            'loginUrl'        => ['/auth/login'],
            'enableAutoLogin' => true,
            'identityCookie'  => [
                'name' => 'BITRIX_SM_UIDH',
            ],
        ],
    ],
], require __DIR__ . '/main.local.php');
