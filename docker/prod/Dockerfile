FROM php:7.1.8-apache

LABEL app="watch-tower-backend"

ENV APP_HOME=/var/www/html

RUN apt-get update

RUN apt-get -y install \
    libpq-dev \
    libmcrypt-dev \
    libzzip-dev \
    zziplib-bin \
    zlib1g-dev \
    mysql-client \
    rsyslog \
    php5-pgsql

# docker-php ext-install:
RUN docker-php-ext-install \
    mcrypt \
    zip \
    pdo_mysql \
    pdo_pgsql \
    pdo \
    pgsql

# rsyslog: (for Loggly etc)
RUN apt-get clean

RUN sed -i '1s/^/$MaxMessageSize 64k\n/' /etc/rsyslog.conf

# install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer

# DIR
WORKDIR ${APP_HOME}

# apache:
COPY docker/prod/vhost.conf /etc/apache2/sites-available/000-default.conf

COPY . ${APP_HOME}

COPY .env ${APP_HOME}/.env

RUN composer install --no-dev
# www-data:
RUN chown -R www-data:www-data ./ && a2enmod rewrite
RUN chown -R www-data:www-data storage

# mode-root:
USER root
