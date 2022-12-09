FROM composer

WORKDIR /vk-bot/

#ENV TEST_ENV_ATTR 'Hello, World'

#RUN install -cd composer

COPY ./backend/ /vk-bot/

#COPY composer.* ./
#RUN cd /vk-bot/ && composer install && composer dump-autoload

#ADD nginx.conf /etc/nginx/conf.d

#COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
#RUN cd ~/vk-bot/ && composer install && composer dump-autoload

CMD bash -c "composer install && composer dump-autoload"

#RUN systemctl restart nginx
#
#RUN pkill php8.1-fpm
#RUN service php8.1-fpm start


#FROM composer AS composer
#FROM php:8-fpm-alpine
#
#ENV TEST_ENV_ATTR 'Hello, World'
#
##RUN install -cd composer
#
#COPY ./backend/ /vk-bot/
#
##COPY composer.* ./
##RUN cd /vk-bot/ && composer install && composer dump-autoload
#
##ADD nginx.conf /etc/nginx/conf.d
#
#COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
##RUN cd ~/vk-bot/ && composer install && composer dump-autoload
#
##WORKDIR /vk-bot/
##
##CMD bash -c "composer install && composer dump-autoload"
#
##RUN systemctl restart nginx
##
##RUN pkill php8.1-fpm
##RUN service php8.1-fpm start
#
#RUN ls
#
##EXPOSE 8080

