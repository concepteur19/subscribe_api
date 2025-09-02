# Dockerfile — Laravel (PHP-FPM + Composer)
FROM php:8.2-cli

# install system deps
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev zip libonig-dev curl \
    libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql pgsql mbstring zip exif pcntl

# install composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# copy app
COPY . /var/www

# install composer deps
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# storage perms
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache || true

EXPOSE 10000

# start app (without migrate to avoid crash if db not ready)
CMD php artisan serve --host=0.0.0.0 --port=${PORT:-10000}