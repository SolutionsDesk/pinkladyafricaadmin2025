FROM php:8.2-fpm
COPY uploads.ini /usr/local/etc/php/conf.d/uploads.ini
# Update the PHP-FPM port
RUN sed -i 's/9000/9325/' /usr/local/etc/php-fpm.d/zz-docker.conf

# Update and install required packages including GD dependencies
RUN apt-get update && apt-get install -y \
    gnupg \
    curl \
    libjpeg-dev \
    libpng-dev \
    libwebp-dev \
    libfreetype6-dev \
    libmagickwand-dev --no-install-recommends \
    libzip-dev \
    openssl \
    zip

# Configure and install PHP extensions
RUN docker-php-ext-configure gd --with-jpeg --with-freetype --with-webp \
    && docker-php-ext-install pdo_mysql bcmath exif gd zip
RUN pecl install imagick && docker-php-ext-enable imagick
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Install Node.js and npm
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set the working directory
WORKDIR /var/www/pinkladyafricaadmin
