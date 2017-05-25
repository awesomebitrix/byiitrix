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

    /**
     * @var \common\bitrix\components\Block
     */
    public static $block;

    /**
     * @var \common\bitrix\components\Element
     */
    public static $element;

    /**
     * @var \common\bitrix\components\Property
     */
    public static $property;

    /**
     * @var \common\bitrix\components\PropertyEnum
     */
    public static $propertyEnum;

    /**
     * @var \common\bitrix\components\Section
     */
    public static $section;
}

spl_autoload_register(['Yii', 'autoload'], true, true);
Yii::$classMap  = require APP_ROOT . '/vendor/yiisoft/yii2/classes.php';
Yii::$container = new Container();
