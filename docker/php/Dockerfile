FROM php:8.3-cli

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        git \
        unzip \
        libicu-dev \
        libzip-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libfreetype6-dev \
        libwebp-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install \
        intl \
        pdo_mysql \
        zip \
        gd \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /app
