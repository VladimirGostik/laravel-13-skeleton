FROM php:8.5-cli AS dev

RUN apt-get update && apt-get install -y \
        git unzip libpq-dev libzip-dev libicu-dev libpng-dev libonig-dev libxml2-dev \
        libcurl4-openssl-dev libssl-dev pkg-config \
    && docker-php-ext-install pdo_pgsql pgsql intl mbstring zip bcmath gd \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
