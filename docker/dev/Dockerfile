FROM dunglas/frankenphp as php-builder

RUN apt-get update &&\
    apt-get install -y git \
        supervisor \
    && apt-get clean

# Install PHP extensions
RUN install-php-extensions pcntl sockets pdo_pgsql zip

# Get latest Composer
COPY --from=composer:2.6.6 /usr/bin/composer /usr/bin/composer

# Get latest Bun
COPY --from=oven/bun:latest /usr/local/bin/bun /usr/local/bin/bun

# Setting workdir
ADD . /var/www/app
WORKDIR /var/www/app

# Copy supervisord config
COPY docker/dev/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

RUN rm -rf /var/cache/apk/*

# Install dependencies
RUN bun install chokidar
RUN composer install

EXPOSE 8089

CMD ["/bin/bash", "./docker/dev/entrypoint.sh"]
