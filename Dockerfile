FROM php:8.0-apache

VOLUME /media
EXPOSE 80

COPY ./src /var/www/html

RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

RUN apt-get update && \
    apt-get install -y exiftool

COPY ./docker/apache/apache.conf /etc/apache2/apache2.conf
COPY ./docker/php/php.ini /usr/local/etc/php/php.ini-production
COPY ./docker/entry.sh /entry.sh

ENTRYPOINT ["/entry.sh"]
