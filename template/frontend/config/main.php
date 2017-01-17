<?php

use yii\log\FileTarget;
use common\web\User;
use common\web\View;
use common\web\Request;
use common\web\Response;

/**
 * Main frontend configuration file
 */

return \yii\helpers\ArrayHelper::merge(require APP_ROOT . '/common/config/main.php', [
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
                    'class'  => FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'response'     => [
            'class' => Response::class,
        ],
        'request'      => [
            'class' => Request::class,
        ],
        'view'         => [
            'class' => View::class,
        ],
        'urlManager'   => [
            'enablePrettyUrl' => true,
            'showScriptName'  => false,
            'rules'           => require __DIR__ . '/rules.php',
        ],
        'user'         => [
            'class'           => User::class,
            'identityClass'   => \common\models\User::class,
            'loginUrl'        => ['/auth/login'],
            'enableAutoLogin' => true,
        ],
    ],
    'defaultRoute'        => 'site/index',
], file_exists(__DIR__ . '/main.local.php') ? require __DIR__ . '/main.local.php' : []);
