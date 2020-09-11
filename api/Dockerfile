FROM php:fpm-alpine
WORKDIR /app
RUN curl -sS https://getcomposer.org/installer | php
RUN docker-php-ext-install pdo_mysql
COPY composer.json .
RUN php composer.phar install
COPY migrations migrations
ENV API_PATH /app
CMD ["sh", "-c", "php migrations/migrate.php && php -S 0.0.0.0:8081 -t public"]
