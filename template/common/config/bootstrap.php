<?php
/**
 */

Yii::setAlias('console', APP_DIR . '/console');
Yii::setAlias('common', APP_DIR . '/common');
Yii::setAlias('public', APP_DIR . '/public_html');
Yii::setAlias('bitrix', APP_DIR . '/public_html/bitrix');
Yii::setAlias('frontend', APP_DIR . '/frontend');
Yii::setAlias('upload', APP_DIR . '/public_html/upload');
Yii::setAlias('byiitrix', APP_DIR . '/vendor/ksaitechnologies/byiitrix');

Yii::$classMap['yii\helpers\ArrayHelper']  = '@byiitrix/helpers/yii/helpers/ArrayHelper.php';
Yii::$classMap['yii\helpers\StringHelper'] = '@byiitrix/helpers/yii/helpers/StringHelper.php';
Yii::$classMap['yii\helpers\Console']      = '@byiitrix/helpers/yii/helpers/Console.php';
