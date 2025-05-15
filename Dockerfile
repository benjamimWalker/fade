FROM php:8.4-fpm-alpine

ARG user=ben
ARG uid=1000

RUN apk add --no-cache $PHPIZE_DEPS \
    zlib-dev \
    oniguruma-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libxml2-dev \
    libzip-dev \
    linux-headers \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd sockets zip \
    && pecl install redis \
    && docker-php-ext-enable redis

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN adduser -G www-data -D -u $uid $user \
 && mkdir -p /home/$user/.composer \
 && chown -R $user:www-data /home/$user

WORKDIR /var/www/html

RUN chmod -R 775 /var/www/html

USER $user
