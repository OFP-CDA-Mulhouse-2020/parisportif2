FROM php:7.4.12-apache

ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e \
        's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
        ${APACHE_CONFDIR}/sites-available/*.conf && \
    sed -ri -e \
        's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' \
        ${APACHE_CONFDIR}/apache2.conf /etc/apache2/conf-available/*.conf

RUN docker-php-ext-install opcache > /dev/null
COPY conf/opcache.ini ${PHP_INI_DIR}/conf/opcache.ini

RUN pecl install xdebug > /dev/null && \
    docker-php-ext-enable xdebug
COPY /conf/xdebug.ini ${PHP_INI_DIR}/conf.d/xdebug.ini

RUN cp "${PHP_INI_DIR}/php.ini-development" "${PHP_INI_DIR}/php.ini"


EXPOSE 80