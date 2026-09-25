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
ENV APP_ENV=production
ENV APP_DEBUG=false
ENV LOG_CHANNEL=stderr
ENV LOG_LEVEL=error

# Composer
ENV COMPOSER_ALLOW_SUPERUSER 1

# Limites do PHP
RUN echo "memory_limit = 512M" > /usr/local/etc/php/conf.d/docker-php-memlimit.ini && \
    echo "max_execution_time = 300" > /usr/local/etc/php/conf.d/docker-php-execution-time.ini

# Criar diretórios + ownership + permissões corretas
RUN mkdir -p \
        storage/framework/{sessions,views,cache/data} \
        storage/logs \
        bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache \
    && chmod -R g+s storage bootstrap/cache   # setgid para novos arquivos herdarem o grupo

# Remover qualquer cache de config que possa forçar o canal de log errado
RUN rm -f bootstrap/cache/*.php

COPY scripts/00-laravel-permissions.sh /scripts/00-laravel-permissions.sh
RUN chmod +x /scripts/00-laravel-permissions.sh

CMD ["/start.sh"]
