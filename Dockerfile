FROM richarvey/nginx-php-fpm:latest

# Instalar Node.js e npm
RUN apk --no-cache add nodejs npm

COPY . .

# Image config
ENV SKIP_COMPOSER 1
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1
ENV REAL_IP_HEADER 1

# Laravel config
ENV APP_ENV production
ENV APP_DEBUG false
ENV LOG_CHANNEL stderr

# Composer
ENV COMPOSER_ALLOW_SUPERUSER 1

# PHP
RUN echo "memory_limit = 512M" > /usr/local/etc/php/conf.d/docker-php-memlimit.ini
RUN echo "max_execution_time = 300" > /usr/local/etc/php/conf.d/docker-php-execution-time.ini

# Permissões Laravel
RUN mkdir -p \
    /var/www/html/storage/framework/sessions \
    /var/www/html/storage/framework/views \
    /var/www/html/storage/framework/cache/data \
    /var/www/html/storage/logs \
    /var/www/html/bootstrap/cache \
    && chown -R www-data:www-data \
        /var/www/html/storage \
        /var/www/html/bootstrap/cache \
    && chmod -R 775 \
        /var/www/html/storage \
        /var/www/html/bootstrap/cache

# Remover configurações/cache antigos
RUN rm -f /var/www/html/bootstrap/cache/config.php \
          /var/www/html/bootstrap/cache/services.php \
          /var/www/html/bootstrap/cache/packages.php \
          /var/www/html/bootstrap/cache/routes-v7.php

CMD ["/start.sh"]
