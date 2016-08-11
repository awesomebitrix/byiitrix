<?php
/**
 * Main common configuration file
 */

return \yii\helpers\ArrayHelper::merge([
   'components'         => [
       'cache'        => [
           'class'     => 'yii\caching\FileCache',
       ],
       'curl'         => [
           'class' => 'common\base\Curl'
       ],
       'db'           => require __DIR__ . '/db.php',
       'mailer'       => [
           'class'    => 'yii\swiftmailer\Mailer',
           'viewPath' => '@common/mail',
       ],
   ],
   'language'           => 'ru',
   'name'               => 'Bitrix via yii2',
   'params'             => require __DIR__ . '/params.php',
   'vendorPath'         => APP_DIR . '/vendor',
], require __DIR__ . '/main.local.php');
