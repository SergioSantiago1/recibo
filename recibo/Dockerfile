FROM php:8.2-apache

# Habilita mod_rewrite (caso precise)
RUN a2enmod rewrite

# Instala extensões necessárias e o Composer
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip git && \
    docker-php-ext-install zip

# Instala o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copia os arquivos do seu projeto
COPY . /var/www/html/

# Instala dependências do PHP (dompdf, etc)
WORKDIR /var/www/html
RUN composer install

# Expõe a porta padrão do Apache
EXPOSE 80
