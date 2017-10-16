FROM php:7.0-apache

COPY httpd.conf /usr/local/apache2/conf/httpd.conf
COPY index.html /usr/local/apache2/htdocs/index.html
COPY phpinfo.php /usr/local/apache2/htdocs/php/phpinfo.php
