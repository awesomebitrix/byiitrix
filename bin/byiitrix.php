<?php
/**
 */

use yii\helpers\Console;

$byiitrixDir = dirname(__DIR__);
$templateDir = $byiitrixDir . '/template';
$autoload    = $byiitrixDir . '/vendor/autoload.php';

$projectRoot = realpath('.');
$webRoot     = $projectRoot;
$withWeb     = false;

/**
 * Check if package installed as dependency and located in vendor directory
 */
if( file_exists($autoload) === false ) {
    $autoload = dirname(dirname($byiitrixDir)) . '/autoload.php';
}

if( file_exists($autoload) === false ) {
    throw new \Exception('Can not resolve composer autoload file.');
}

require $autoload;

if( class_exists('yii\helpers\Console') === false ) {
    throw new \Exception('Can not resolve yii core classes.');
}

$withWeb = Console::confirm('Generate web template and configurations?', ['default' => $withWeb]);

if( Console::confirm("Directory \"{$projectRoot}\" is project root?", ['default' => true]) === false ) {
    do {
        $path = Console::prompt('Specify project root path:', ['required' => true]);

        if( is_dir($path) === false ) {
            echo 'Current directory not exists' . PHP_EOL;
            continue;
        }

        $path = realpath($path);
    } while( Console::confirm("Directory \"{$path}\" is project root. Confirm?") === false );

    $projectRoot = $path;
}

if( $withWeb && Console::confirm('Project root is web server root?') === false ) {
    do {
        $path = Console::prompt('Specify web root path:', ['required' => true, 'default' => 'public_html']);

        if( is_dir($path) === false ) {
            echo 'Current directory not exists' . PHP_EOL;
            continue;
        }

        $path = realpath($path);
    } while( Console::confirm("Directory \"{$path}\" is web root. Confirm?", ['default' => true]) === false );

    $webRoot = $path;
}

echo 'Copy template files in project root...' . PHP_EOL;

$commands = [
    "cp -r {$templateDir}/yii.php {$projectRoot}/",
    "cp -r {$templateDir}/yii {$projectRoot}/",
    "cp -r {$templateDir}/common {$projectRoot}/",
    "cp -r {$templateDir}/console {$projectRoot}/",
    $withWeb ? "cp -r {$templateDir}/frontend {$projectRoot}/" : NULL,
    $withWeb ? "cp -r {$templateDir}/public_html/app {$webRoot}/" : NULL,
];

foreach( $commands as $command ) {
    if( $command !== NULL ) {
        echo '    ' . Console::ansiFormat($command, [Console::FG_GREEN]) . PHP_EOL;
        exec($command);
    }
}

$ignores = [
    '/common/config/*.local.php',
    '/console/config/*.local.php',
    $withWeb ? '/frontend/config/*.local.php' : NULL,
];

echo 'Add local files to gitignore' . PHP_EOL;

$gitIgnore        = $projectRoot . '/.gitignore';
$gitignoreContent = file_get_contents($gitIgnore);

foreach( $ignores as $ignore ) {
    if( $ignore !== NULL && preg_match('#' . preg_quote($ignore, '#') . '#', $gitignoreContent) === 0 ) {
        file_put_contents($gitIgnore, $ignore . PHP_EOL, FILE_APPEND);
    }
}

if( $withWeb ) {
    $webIndexContent = <<<TEXT
<?php

require \$_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';

require '{$projectRoot}/common/config/define.php';

require APP_DIR . '/vendor/autoload.php';
require APP_DIR . '/vendor/yiisoft/yii2/Yii.php';
require APP_DIR . '/common/config/bootstrap.php';

\$config = require APP_DIR . '/frontend/config/main.php';

\$application = new \common\web\Application(\$config);
\$application->run();

require \$_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_after.php';

TEXT;

    echo 'Create /app/index.php in web root' . PHP_EOL;

    file_put_contents($webRoot . '/app/index.php', $webIndexContent);

    $nginxConfig = <<<TEXT
server {
    listen                          0.0.0.0:80;
    server_name                     localhost;
    root                            {$webRoot};
    index                           index.php index.html;
    access_log                      /var/log/nginx/access.log;
    error_log                       /var/log/nginx/error.log;

    include                         /etc/nginx/mime.types;

    location / {
        try_files                   \$uri \$uri/ /index.php\$is_args\$args =404;

        if (!-e \$request_filename) {
            rewrite  ^(.*)\$  /bitrix/urlrewrite.php last;
        }
    }

    location /app {
        try_files                   \$uri \$uri/ @byiitrix;
    }

    location @byiitrix {
        try_files                   /app/index.php\$is_args\$args =404;

        fastcgi_split_path_info     ^(.+\.php)(/.+)\$;
        fastcgi_pass                127.0.0.1:9000;
        fastcgi_param               PHP_VALUE open_basedir="/tmp:/bin:/dev/urandom:{$projectRoot}";

        include                     fastcgi_params;
    }

    location ~* \.php\$ {
        try_files                   \$uri =404;

        fastcgi_split_path_info     ^(.+\.php)(/.+)\$;
        fastcgi_pass                127.0.0.1:9000;
        fastcgi_param               PHP_VALUE open_basedir="/tmp:/bin:/dev/urandom:{$projectRoot}";

        include                     fastcgi_params;
    }


    location ~* ^.+\.(jpg|jpeg|gif|png|svg|js|css|mp3|ogg|mpe?g|avi|zip|gz|bz2?|rar|swf)\$ {
        try_files         \$uri =404;
        access_log        off;
        expires           max;
    }

    location ~ (/\.ht|/bitrix/modules|/upload/support/not_image) {
        deny all;
    }

    location ~ /\. { deny all; }
}

TEXT;

    echo "Generate nginx vhost configuration stub in {$projectRoot}/nginx.conf" . PHP_EOL;

    file_put_contents($projectRoot . '/nginx.conf', $nginxConfig);

    $config  = $projectRoot . '/frontend/config/main.local.php';
    $bytes   = openssl_random_pseudo_bytes(32);
    $key     = strtr(substr(base64_encode($bytes), 0, 32), '+/=', '_-.');
    $content = preg_replace('/(("|\')cookieValidationKey("|\')\s*=>\s*)(""|\'\')/', "\\1'{$key}'", file_get_contents($config));

    file_put_contents($config, $content);
}
