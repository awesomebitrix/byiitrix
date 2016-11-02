Options -Indexes
ErrorDocument 404 /404.php

<IfModule mod_php5.c>
  php_flag session.use_trans_sid off
</IfModule>

<IfModule mod_rewrite.c>
  Options +FollowSymLinks
  RewriteEngine On
  RewriteCond %{HTTP_USER_AGENT} "MSIE [6-9]" [NC]
  RewriteCond %{REQUEST_FILENAME} !/old-browser.html$
  RewriteCond %{REQUEST_FILENAME} !/old-browser/
  RewriteRule ^(.*)$ /old-browser.html [R=301,L]

  RewriteCond %{REQUEST_FILENAME} !-f
  RewriteCond %{REQUEST_FILENAME} !-l
  RewriteCond %{REQUEST_FILENAME} !-d
  RewriteCond %{REQUEST_FILENAME} /app/
  RewriteRule ^(.*)$ /app/index.php [L]

  RewriteCond %{REQUEST_FILENAME} !-f
  RewriteCond %{REQUEST_FILENAME} !-l
  RewriteCond %{REQUEST_FILENAME} !-d
  RewriteCond %{REQUEST_FILENAME} !/bitrix/urlrewrite.php$
  RewriteRule ^(.*)$ /bitrix/urlrewrite.php [L]
  RewriteRule .* - [E=REMOTE_USER:%{HTTP:Authorization}]
</IfModule>

<IfModule mod_dir.c>
  DirectoryIndex index.php index.html
</IfModule>

<IfModule mod_expires.c>
  ExpiresActive on
  ExpiresByType image/jpeg "access plus 3 day"
  ExpiresByType image/gif "access plus 3 day"
  ExpiresByType image/png "access plus 3 day"
  ExpiresByType text/css "access plus 3 day"
  ExpiresByType application/javascript "access plus 3 day"
</IfModule>
