FROM php:8.2.0-cli
# Define User
RUN usermod -u 1000 www-data
# Install php and extensions
RUN apt-get update && apt-get install -y \
      apt-utils \
      libpq-dev \
      libpng-dev \
      libzip-dev \
      zip unzip \
      git && \
      docker-php-ext-install pdo_mysql && \
      docker-php-ext-install bcmath && \
      docker-php-ext-install gd && \
      docker-php-ext-install zip && \
      apt-get clean && \
      rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

WORKDIR /var/www

COPY --chown=www-data:www-data . .

COPY ./_docker/app/php.ini /usr/local/etc/php/conf.d/php.ini

# Expose port 8000 for the PHP built-in server
EXPOSE 8000

CMD ["php", "-S", "0.0.0.0:8000", "-t", "/var/www/public"]
