<?php

use yii\BaseYii;
use yii\di\Container;

require APP_ROOT . '/vendor/yiisoft/yii2/BaseYii.php';

class Yii extends BaseYii
{
    /**
     * @var \common\console\Application|\common\web\Application|\common\bitrix\Application
     */
    public static $app;
    public static $aliases = ['@yii' => APP_ROOT . '/vendor/yiisoft/yii2'];
}

spl_autoload_register(['Yii', 'autoload'], true, true);
Yii::$classMap  = require APP_ROOT . '/vendor/yiisoft/yii2/classes.php';
Yii::$container = new Container();
