FROM php:8.2-cli

# Instala dependencias necesarias
RUN apt-get update && apt-get install -y \
    unzip \
    libzip-dev \
    libpq-dev \
    zip \
    git \
    curl \
    && docker-php-ext-install pdo pdo_pgsql zip

# Instala Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Define directorio de trabajo
WORKDIR /var/www/html

# Copia el proyecto
COPY . .

# Instala dependencias Laravel
RUN composer install --no-dev --optimize-autoloader

# Expone el puerto 10000 (Render lo requiere)
EXPOSE 10000

# Comando para ejecutar Laravel
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=10000"]

RUN php artisan storage:link
