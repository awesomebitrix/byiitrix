<?php
/**
 * Byiitrix installer templates files right in your bitrix project
 */

use yii\helpers\Console;

$byiitrixRoot = dirname(__DIR__);
$vendorRoot   = dirname(dirname($byiitrixRoot));
$projectRoot  = dirname($vendorRoot);

require $vendorRoot . '/autoload.php';

while( Console::confirm("Directory \"{$projectRoot}\" is project root. Confirm?", true) === false ) {
    read_project_root:
    $path = Console::prompt('Specify project root path:', ['required' => true]);
    $path = realpath($path);

    if( $path === false ) {
        echo 'Directory not exists' . PHP_EOL;
        goto read_project_root;
    }

    if( is_dir($path) === false ) {
        echo "Directory {$path} is not directory" . PHP_EOL;
        goto read_project_root;
    }

    if( is_writable($path) === false ) {
        echo "Directory {$path} is not writable" . PHP_EOL;
        goto read_project_root;
    }

    $projectRoot = $path;
}

echo 'Copy template files in project root...' . PHP_EOL;

exec("cp -r {$byiitrixRoot}/template/* {$projectRoot}/");

$config  = $projectRoot . '/frontend/config/main.local.php';
$key     = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x', mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0x0fff) | 0x4000, mt_rand(0, 0x3fff) | 0x8000, mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff));
$content = preg_replace('/(("|\')cookieValidationKey("|\')\s*=>\s*)(""|\'\')/', "\\1'{$key}'", file_get_contents($config));

file_put_contents($config, $content);
