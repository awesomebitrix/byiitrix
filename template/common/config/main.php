<?php
/**
 * Main common configuration file
 */

$local = str_replace('.php', '.local.php', __FILE__);

return \yii\helpers\ArrayHelper::merge([
    'components' => [
        'cache'  => [
            'class' => 'yii\caching\FileCache',
        ],
        'db'     => require __DIR__ . '/db.php',
        'mailer' => [
            'class'    => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
        ],
    ],
    'language'   => 'ru',
    'name'       => 'Bitrix via yii2',
    'params'     => require __DIR__ . '/params.php',
    'vendorPath' => APP_DIR . '/vendor',
], file_exists($local) ? require $local : []);
