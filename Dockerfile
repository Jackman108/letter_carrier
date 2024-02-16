# server/Dockerfile
FROM php:latest

# Install additional packages and MongoDB extension
RUN apt-get update && \
    apt-get install -y \
        libssl-dev \
        libsasl2-dev && \
    pecl install mongodb && \
    docker-php-ext-enable mongodb

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . /var/www/html

# Enable MongoDB PHP extension in PHP configuration
RUN echo "extension=mongodb.so" > /usr/local/etc/php/conf.d/mongodb.ini

# Command to run PHP server
CMD ["php", "-S", "0.0.0.0:9000"]
