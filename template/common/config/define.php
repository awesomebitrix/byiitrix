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
