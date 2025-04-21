# Usa a imagem oficial do PHP com Apache
FROM php:8.2-apache

# Habilita mod_rewrite (caso necessário)
RUN a2enmod rewrite

# Instala as dependências necessárias e as extensões do PHP
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip git && \
    docker-php-ext-install zip && \
    rm -rf /var/lib/apt/lists/*  # Limpeza do cache do apt para reduzir o tamanho da imagem

# Instala o Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copia os arquivos da aplicação para o diretório do Apache no contêiner
COPY . /var/www/html/

# Altera o diretório de trabalho para onde os arquivos foram copiados
WORKDIR /var/www/html

# Instala as dependências do Composer
RUN composer install --no-interaction --optimize-autoloader

# Ajusta permissões para que o Apache tenha acesso aos arquivos
RUN chown -R www-data:www-data /var/www/html

# Expondo a porta 80 do Apache (padrão)
EXPOSE 80

# Comando para iniciar o Apache
CMD ["apache2-foreground"]