#!/usr/bin/env bash
echo "Running composer"
composer global require hirak/prestissimo
composer install --no-dev --working-dir=/var/www/html

echo "generating application key..."
php artisan key:generate --show

echo "Caching config..."
php artisan config:cache

echo "Caching routes..."
php artisan route:cache

echo "Running migrations..."
php artisan migrate --force 

echo "Running seed..."
php artisan db:seed --force

echo "Running vite..."
npm install
npm run build

echo "max_execution_time = 300..."
RUN echo "max_execution_time = 300" > /usr/local/etc/php/conf.d/docker-php-execution-time.ini

# Ref: https://community.render.com/t/gatsby-build-caching-and-image-transformations/129/2

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

echo "done deploying 🚀"
