FROM php:8.1-fpm

COPY . /vk-bot/

RUN apt-get update

RUN apt-get install -y libpq-dev \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /vk-bot/

#RUN apt-get install composer
RUN composer install && composer dump-autoload

CMD ["php-fpm"]

EXPOSE 9000
