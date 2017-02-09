<?php
/**
 * Main application definitions
 */

if( file_exists(__DIR__ . '/define.local.php') ) {
    require __DIR__ . '/define.local.php';
}

error_reporting(0);

defined('YII_DEBUG') or define('YII_DEBUG', false);
defined('YII_ENV') or define('YII_ENV', 'prod');

if( class_exists('Kint') ) {
    Kint::enabled(YII_ENV !== 'prod');

    function kint()
    {
        $args = func_get_args();

        return call_user_func_array(['Kint', 'dump'], $args);
    }
}
