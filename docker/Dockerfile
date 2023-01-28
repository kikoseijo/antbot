ARG PHP_VERSION=8.1

FROM ubuntu:22.04 as build-php

ARG DEBIAN_FRONTEND=noninteractive # Disable prompt of apt
RUN apt update && apt install -y --no-install-recommends \
    curl                                \
    unzip                               \
    ca-certificates                     \
    php${ANTBOT_PHP_VERSION}            \
    php${ANTBOT_PHP_VERSION}-xml        \
    php${ANTBOT_PHP_VERSION}-curl       \
    php${ANTBOT_PHP_VERSION}-mbstring

RUN curl -sS https://getcomposer.org/installer -o /tmp/composer-setup.php
RUN php /tmp/composer-setup.php --install-dir=/usr/bin --filename=composer


WORKDIR /home/app

COPY .. /home/app/
RUN chown -R www-data:www-data /home/app
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN composer install --no-dev --prefer-dist --optimize-autoloader --ignore-platform-reqs

FROM php:${PHP_VERSION}-fpm

ARG PYTHON_VERSION=3.9

RUN apt update -y &&                    \
    apt install -y                      \
    libicu-dev                          \
    python${PYTHON_VERSION}             \
    python${PYTHON_VERSION}-distutils

ENV PYTHON_PATH=python${PYTHON_VERSION}
RUN curl https://bootstrap.pypa.io/get-pip.py | $PYTHON_PATH

RUN docker-php-ext-install pdo_mysql intl
RUN docker-php-ext-enable pdo_mysql intl

# =====================================
# Health check configuration
RUN curl -o /usr/local/bin/php-fpm-healthcheck \
    https://raw.githubusercontent.com/renatomefi/php-fpm-healthcheck/master/php-fpm-healthcheck \
    && chmod +x /usr/local/bin/php-fpm-healthcheck

RUN set -x \
    && apt install -y libfcgi-bin

# Enable php fpm status page
RUN set -xe && echo "pm.status_path = /status" >> /usr/local/etc/php-fpm.d/zz-docker.conf
# =====================================

ARG PASSIVBOT_PATH
ARG PASSIVBOT_LOGS_PATH

COPY ../submodules/passivbot ${PASSIVBOT_PATH}
RUN $PYTHON_PATH -m pip install -r ${PASSIVBOT_PATH}/requirements_liveonly.txt
RUN chown -R www-data:www-data ${PASSIVBOT_PATH}
RUN mkdir ${PASSIVBOT_LOGS_PATH} && chown -R www-data:www-data ${PASSIVBOT_LOGS_PATH}

WORKDIR /var/www/html

COPY --from=build-php /home/app /var/www/html
COPY docker/.env /var/www/html/.env
CMD php artisan migrate --force && php-fpm
