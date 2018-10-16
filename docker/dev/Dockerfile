FROM laradock/php-fpm:2.2-7.2

LABEL application="watchtower-backend"

ENV APP_HOME=/var/www
WORKDIR /var/www

# always run apt update when start and after add new source list, then clean up at end.
RUN apt-get update -yqq && \
    apt-get install -y apt-utils git-core netcat libzip-dev && \
    pecl channel-update pecl.php.net && \
    docker-php-ext-configure zip --with-libzip && \
    docker-php-ext-install zip && \
    pecl install xdebug && docker-php-ext-enable xdebug

COPY . ${APP_HOME}

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN composer install

