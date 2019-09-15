# This file is making a Wordpress Docker image for developer which includes xDebug, MailHog, ZipArchive, and increased upload size
# Make this like docker build -t sujin2f/wordpress .

FROM wordpress:php7.3

# Install x-debug
RUN apt-get update \
  && pecl install xdebug \
  && docker-php-ext-enable xdebug

# ZipArchive
RUN apt-get install -y \
    libzip-dev \
    zip \
  && docker-php-ext-configure zip --with-libzip \
  && docker-php-ext-install zip

# Increase Upload Size
RUN echo 'file_uploads = On\nmemory_limit = 100M\nupload_max_filesize = 100M\npost_max_size = 100M\nmax_execution_time = 1000' > /usr/local/etc/php/conf.d/uploads.ini
