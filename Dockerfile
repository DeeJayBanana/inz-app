FROM php:8.2-fpm

# Instalacja zależności systemowych
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    git \
    unzip \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_mysql gd

# ZMIANA: Instalacja Node.js 22 (Vite wymaga min. 20)
RUN curl -sL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs

WORKDIR /var/www

# Instalacja Composera
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Kopiowanie plików (bez node_modules i vendor - lepiej je zainstalować w kontenerze)
COPY . .

# Uprawnienia (ważne dla Dockera)
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

COPY uploads.ini /usr/local/etc/php/conf.d/uploads.ini

RUN composer install --no-interaction --optimize-autoloader

# Opcjonalnie: Budowanie assetów produkcyjnych (jeśli nie używasz npm run dev)
# RUN npm install && npm run build

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
