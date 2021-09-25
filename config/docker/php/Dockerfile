FROM php:8.0-fpm
RUN apt update && apt install -y libpq-dev libicu-dev libpng-dev libjpeg62-turbo-dev libfreetype6-dev
RUN docker-php-ext-configure gd --with-jpeg
RUN docker-php-ext-install pdo_pgsql intl gd
RUN echo "date.timezone = Europe/London" >> /usr/local/etc/php/conf.d/timezone.ini
RUN echo "error_reporting = E_ALL" >> /usr/local/etc/php/conf.d/errors.ini
RUN echo "display_errors = On" >> /usr/local/etc/php/conf.d/errors.ini
RUN echo "display_startup_errors = On" >> /usr/local/etc/php/conf.d/errors.ini
RUN echo "upload_max_filesize = 64M" >> /usr/local/etc/php/conf.d/upload.ini
RUN echo "post_max_size = 64M" >> /usr/local/etc/php/conf.d/post.ini
