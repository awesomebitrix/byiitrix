<?php
/**
 * Main application definitions
 */

$local = str_replace('.php', '.local.php', __FILE__);

if( file_exists($local) ) {
    require $local;
}

error_reporting(0);

define('APP_DIR', dirname(dirname(__DIR__)));

defined('YII_DEBUG') or define('YII_DEBUG', false);
defined('YII_ENV') or define('YII_ENV', 'prod');
