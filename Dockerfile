FROM elrincondeisma/octane:latest

# Instala curl, nano y Node.js (para NPM y Vite)
RUN apk add --no-cache curl nano nodejs npm

# Instala Composer globalmente
RUN curl -sS https://getcomposer.org/installer​ | php -- \
     --install-dir=/usr/local/bin --filename=composer

# Copia Composer y RoadRunner desde imágenes existentes
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY --from=spiralscout/roadrunner:2.4.2 /usr/bin/rr /usr/bin/rr

WORKDIR /app

# Copia tu aplicación al directorio de trabajo
COPY . .

# Limpia directorios y archivos previos de Composer
RUN rm -rf /app/vendor
RUN rm -rf /app/composer.lock

# Instala dependencias de Composer
RUN composer install

# Instala Laravel Octane y RoadRunner
RUN composer require laravel/octane spiral/roadrunner

# Prepara el archivo .env
COPY .env.example .env

# Crea el directorio de logs
RUN mkdir -p /app/storage/logs

# Limpia la cache de Laravel y configura
RUN php artisan cache:clear
RUN php artisan view:clear
RUN php artisan config:clear

# Instala Laravel Octane con Swoole
RUN php artisan octane:install --server="swoole"

# Instala dependencias de NPM y construye con Vite
RUN npm install
RUN npm run build

# Ejecuta migraciones de Laravel
RUN php artisan migrate

# Comando para iniciar Laravel Octane con Swoole
CMD php artisan octane:start --server="swoole" --host="0.0.0.0"

# Expone el puerto 8000
EXPOSE 8000
