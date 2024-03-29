#!/usr/bin/env bash
echo "Running composer"
composer global require hirak/prestissimo

echo "Composer udpate..."
composer update

echo "Composer install..."
composer install --no-dev --working-dir=/var/www/html

echo "generating application key..."
php artisan key:generate --show

echo "Caching config..."
php artisan config:cache

echo "Caching routes..."
php artisan route:cache

echo "Running migrations and seeds..."
php artisan migrate:refresh --seed --force 

echo "Running vite..."
npm install
npm run build

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
