FROM richarvey/nginx-php-fpm:latest

# Instalar o Node.js e npm
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

# Allow composer to run as root
ENV COMPOSER_ALLOW_SUPERUSER 1

# Ref: https://community.render.com/t/gatsby-build-caching-and-image-transformations/129/2
# Set the memory limit for PHP
RUN echo "memory_limit = 512M" > /usr/local/etc/php/conf.d/docker-php-memlimit.ini

RUN echo "max_execution_time = 300" > /usr/local/etc/php/conf.d/docker-php-execution-time.ini

restore_render_cache() {
  local source_cache_dir="$1"
  if [[ -d "$XDG_CACHE_HOME/$source_cache_dir" ]]; then
    echo "CACHE HIT $source_cache_dir, rsyncing..."
    rsync -a "$XDG_CACHE_HOME/$source_cache_dir/" $source_cache_dir
  else
    echo "CACHE MISS $source_cache_dir"
  fi
}

save_render_cache() {
  local source_cache_dir="$1"
  echo "CACHE SAVE $source_cache_dir, rsyncing..."
  mkdir -p "$XDG_CACHE_HOME/$source_cache_dir"
  rsync -a $source_cache_dir/ "$XDG_CACHE_HOME/$source_cache_dir"
}

install_and_build_with_cache() {
  restore_render_cache "node_modules"
  yarn --frozen-lockfile --production
  save_render_cache "node_modules"

  restore_render_cache ".cache"
  restore_render_cache "public"
  export GATSBY_EXPERIMENTAL_PAGE_BUILD_ON_DATA_CHANGES=true
  yarn gatsby build
  save_render_cache ".cache"
  save_render_cache "public"
}

install_and_build_with_cache

CMD ["/start.sh"]
