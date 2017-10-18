FROM php:7.0-apache

COPY index.html /var/www/html/index.html
COPY phpinfo.php /var/www/html/php/phpinfo.php
COPY htdocs/* /var/www/html/htdocs/


#FROM httpd:2.4
#COPY httpd.conf /usr/local/apache2/conf/httpd.conf
#COPY index.html /usr/local/apache2/htdocs/index.html
