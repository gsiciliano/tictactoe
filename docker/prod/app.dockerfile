FROM php:7.4.2-fpm

RUN apt-get update && apt-get install -y libmcrypt-dev libaio-dev libxslt1-dev libzip-dev sqlite3

RUN docker-php-ext-install pdo_mysql \
 && pecl install mcrypt-1.0.3 \
 && docker-php-ext-enable mcrypt \
 && docker-php-ext-install xsl \
 && docker-php-ext-install zip 

RUN mkdir -p /home/www-data/.composer \
 && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
    
ADD ./src /var/www/
ADD ./src/.env.example /var/www/.env
RUN touch /var/www/database/database.sqlite
RUN touch /var/www/database/test.sqlite

WORKDIR /var/www

RUN composer install

RUN chown -R www-data:www-data /var/www \
 && chmod -R 777 /var/www/storage

RUN php artisan migrate --seed \ 
 && php artisan passport:install


USER www-data
