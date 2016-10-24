<?php
/**
 * Byiitrix installer templates files right in your bitrix project
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
    top:
    do {
        $path = Console::prompt('Specify project root path:', ['required' => true]);

        if( is_dir($path) === false ) {
            echo "Directory {$path} not exists" . PHP_EOL;
            goto top;
        }

        $path = realpath($path);
    } while( Console::confirm("Directory \"{$path}\" is project root. Confirm?") === false );

    $projectRoot = $path;
}

if( $withWeb && Console::confirm('Project root is web server root?') === false ) {
    top2:
    do {
        $path = Console::prompt('Specify web root path:', ['required' => true, 'default' => 'public_html']);

        if( strpos($path, $projectRoot) !== 0 ) {
            $path = preg_replace('#//+#', '/', rtrim($projectRoot . '/' . $path, '/'));
        }

        if( is_dir($path) === false ) {
            echo "Directory {$path} not exists" . PHP_EOL;
            goto top2;
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
    $projectDir = 'realpath($_SERVER[\'DOCUMENT_ROOT\'])';
    $dirParts   = explode('/', trim(str_replace($projectRoot, '', $webRoot), '/'));

    for( $i = count($dirParts); $i > 0; --$i ) {
        $projectDir = "dirname({$projectDir})";
    }

    $webIndexContent = <<<TEXT
<?php

require \$_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';

require {$projectDir} . '/common/config/define.php';

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

    $config  = $projectRoot . '/frontend/config/main.local.php';
    $bytes   = openssl_random_pseudo_bytes(32);
    $key     = strtr(substr(base64_encode($bytes), 0, 32), '+/=', '_-.');
    $content = preg_replace('/(("|\')cookieValidationKey("|\')\s*=>\s*)(""|\'\')/', "\\1'{$key}'", file_get_contents($config));

    file_put_contents($config, $content);

    if( Console::confirm("Generate nginx configuration stub example in {$projectRoot}/nginx.conf?", ['default' => false]) ) {
        $nginxConfig = <<<TEXT
server {
    listen                          80;
    server_name                     localhost;
    root                            {$webRoot};
    index                           index.php index.html;
    access_log                      /var/log/nginx/access.log;
    error_log                       /var/log/nginx/error.log;

    include                         /etc/nginx/mime.types;

    location / {
        set \$go_old "";

        if (\$http_user_agent ~* '(MSIE 9.0|MSIE 8.0|MSIE 7.0)') {
            set \$go_old "\${go_old}1";
        }

        if (\$uri !~ "^/old-browser.html") {
            set \$go_old "\${go_old}2";
        }

        if (\$go_old = 12) {
            return                  301 /old-browser.html;
        }
        
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

        include                     fastcgi_params;
    }

    location ~* \.php\$ {
        try_files                   \$uri =404;

        fastcgi_split_path_info     ^(.+\.php)(/.+)\$;
        fastcgi_pass                127.0.0.1:9000;
        fastcgi_param               PHP_VALUE "
                                        mbstring.internal_encoding=UTF-8
                                        mbstring.func_overload=2
                                        opcache.revalidate_freq=0
                                        display_errors=Off
                                        error_reporting=0
                                    ";

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

        file_put_contents($projectRoot . '/nginx.conf', $nginxConfig);
    }
}
