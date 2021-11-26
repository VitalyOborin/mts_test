FROM php:8.0-fpm

RUN apt-get update && apt-get install -y \
        git \
        curl \
        wget \
        zip  \
        unzip \
        rsync \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        zlib1g-dev \
        libicu-dev \
        g++ \
        libxml2-dev \
        libzip-dev \
        libwebp-dev \
        librabbitmq-dev \
        gnupg \
        gnupg2 \
        gnupg1 \
        libsqlite3-dev \
        libcurl4-openssl-dev \
        nghttp2
RUN docker-php-ext-configure intl && docker-php-ext-install bcmath intl zip opcache pdo_mysql mysqli sockets
RUN wget https://getcomposer.org/installer -O - -q | php -- --install-dir=/bin --filename=composer --quiet
RUN wget https://get.symfony.com/cli/installer -O - | bash
RUN mv /root/.symfony/bin/symfony /usr/local/bin/symfony
RUN chmod 777 /usr/local/bin/symfony
ADD config/docker/php/php.ini /usr/local/etc/php/php.ini
ADD config/docker/php/php-fpm.conf /usr/local/etc/php-fpm.conf
ADD config/docker/php/php-fpm.www.conf /usr/local/etc/php-fpm.d/www.conf

COPY --chown=www-data . /app
WORKDIR /app
RUN chown -R www-data:www-data /run

RUN php bin/console doctrine:schema:create
RUN php bin/console doctrine:fixtures:load -q
RUN chown -R www-data:www-data /app/var
USER "www-data:www-data"
CMD ["php-fpm"]
HEALTHCHECK --timeout=10s CMD curl --silent --fail http://127.0.0.1:$PORT/fpm-ping || exit 1
