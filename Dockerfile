FROM php:7.0-apache

COPY httpd.conf /usr/local/apache2/conf/httpd.conf
COPY index.html /usr/local/apache2/htdocs/index.html
COPY phpinfo.php /usr/local/apache2/htdocs/php/phpinfo.php

COPY index.html /var/www/html/index.html
COPY phpinfo.php /var/www/html/php/phpinfo.php
