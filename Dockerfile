FROM dunglas/frankenphp

RUN apt-get update &&\
    apt-get install -y git \
        supervisor \
    && apt-get clean

RUN install-php-extensions pcntl sockets pdo_pgsql zip

# Get latest Composer
COPY --from=composer:2.6.6 /usr/bin/composer /usr/bin/composer

# setting workdir
ADD . /var/www/app
WORKDIR /var/www/app

COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

RUN rm -rf /var/cache/apk/*

RUN composer install --no-dev --no-interaction

EXPOSE 80

CMD ["/bin/bash", "./docker/entrypoint.sh"]
