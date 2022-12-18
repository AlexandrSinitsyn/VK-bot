FROM php:8.1-fpm

COPY . /vk-bot/

RUN apt-get update

RUN apt-get install -y libpq-dev \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql

RUN apt-get install -y libz-dev libmemcached-dev && \
    pecl install memcached && \
    docker-php-ext-enable memcached

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /vk-bot/

RUN composer install && composer dump-autoload

CMD ["php-fpm"]

EXPOSE 9000
