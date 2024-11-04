FROM php:8.1.0-apache

WORKDIR /var/www/html

RUN a2enmod rewrite
RUN apt-get update && apt-get install -y \
  unzip \
  libicu-dev \
  libpng-dev \
  libmariadb-dev \
  libfreetype6-dev \
  libonig-dev \
  libjpeg-dev \
  && docker-php-ext-configure gd --enable-gd --with-freetype --with-jpeg \
  && docker-php-ext-install intl pdo pdo_mysql gd mbstring

COPY . /var/www/html

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install

EXPOSE 80