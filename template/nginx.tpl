server {
    listen                          80;
    server_name                     localhost;
    root                            #WEB_ROOT#;
    index                           index.php index.html;
    access_log                      /var/log/nginx/access.log;
    error_log                       /var/log/nginx/error.log;

    include                         /etc/nginx/mime.types;

    location / {
        set $go_old "";

        if ($http_user_agent ~* '(MSIE 9.0|MSIE 8.0|MSIE 7.0)') {
            set $go_old "${go_old}1";
        }

        if ($uri !~ "^/old-browser.html") {
            set $go_old "${go_old}2";
        }

        if ($go_old = 12) {
            return                  301 /old-browser.html;
        }

        try_files                   $uri $uri/ /index.php$is_args$args =404;

        if (!-e $request_filename) {
            rewrite  ^(.*)$  /bitrix/urlrewrite.php last;
        }
    }

    location /app {
        try_files                   $uri $uri/ @byiitrix;
    }

    location @byiitrix {
        try_files                   /app/index.php$is_args$args =404;

        fastcgi_split_path_info     ^(.+\.php)(/.+)$;
        fastcgi_pass                #PHP_LISTEN_PATH#;

        include                     fastcgi_params;
    }

    location ~* \.php$ {
        try_files                   $uri =404;

        fastcgi_split_path_info     ^(.+\.php)(/.+)$;
        fastcgi_pass                #PHP_LISTEN_PATH#;
        fastcgi_param               PHP_VALUE "
                                        mbstring.internal_encoding=UTF-8
                                        mbstring.func_overload=2
                                        opcache.revalidate_freq=0
                                        display_errors=Off
                                        error_reporting=0
                                    ";

        include                     fastcgi_params;
    }


    location ~* ^.+\.(jpg|jpeg|gif|png|svg|js|css|mp3|ogg|mpe?g|avi|zip|gz|bz2?|rar|swf)$ {
        try_files         $uri =404;
        access_log        off;
        expires           max;
    }

    location ~ (/\.ht|/bitrix/modules|/upload/support/not_image) {
        deny all;
    }

    location ~ /\. { deny all; }
}
