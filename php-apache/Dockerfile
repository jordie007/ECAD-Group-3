FROM php:8.0-apache
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli
RUN apt-get update && apt-get upgrade -y

COPY ./httpd.conf /usr/local/apache2/conf/httpd.conf
COPY ./custom.ini $PHP_INI_DIR/conf.d/custom.ini
