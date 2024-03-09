FROM richarvey/nginx-php-fpm:2.0.0

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

# Allow composer to run as root
ENV COMPOSER_ALLOW_SUPERUSER 1

# Set the memory limit for PHP
RUN echo "memory_limit = 512M" > /usr/local/etc/php/conf.d/docker-php-memlimit.ini

CMD ["/start.sh"]
