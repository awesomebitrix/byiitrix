<?php
/**
 */

Yii::setAlias('console', APP_ROOT . '/console');
Yii::setAlias('common', APP_ROOT . '/common');
Yii::setAlias('public', APP_ROOT . '/public_html');
Yii::setAlias('bitrix', APP_ROOT . '/public_html/bitrix');
Yii::setAlias('frontend', APP_ROOT . '/frontend');
Yii::setAlias('upload', WEB_ROOT . '/upload');
Yii::setAlias('core', WEB_ROOT . '/local/php_interface/core');
Yii::setAlias('byiitrix', APP_ROOT . '/vendor/ksaitechnologies/byiitrix');

Yii::$classMap['yii\helpers\ArrayHelper']  = '@byiitrix/yii/helpers/ArrayHelper.php';
Yii::$classMap['yii\helpers\StringHelper'] = '@byiitrix/yii/helpers/StringHelper.php';
Yii::$classMap['yii\helpers\Console']      = '@byiitrix/yii/helpers/Console.php';

if( class_exists(Kint::class) ) {
    Kint::$enabled_mode = YII_ENV !== 'prod';

    function kint()
    {
        $args = func_get_args();

        return call_user_func_array([Kint::class, 'dump'], $args);
    }
}
