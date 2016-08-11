<?php
/**
 * Main application definitions
 */

require __DIR__ . '/define.local.php';

error_reporting(0);

define('APP_DIR', dirname(dirname(__DIR__)));

defined('YII_DEBUG') or define('YII_DEBUG', false);
defined('YII_ENV') or define('YII_ENV', 'prod');
