FROM php:7.4.2-fpm

ARG USER
ARG USERID
ARG GID
ARG OS

RUN apt-get update && apt-get install -y libmcrypt-dev libaio-dev libxslt1-dev libzip-dev sqlite3

RUN docker-php-ext-install pdo_mysql \
    && pecl install mcrypt-1.0.3 \
    && docker-php-ext-enable mcrypt \
    && docker-php-ext-install xsl \
    && docker-php-ext-install zip 

RUN mkdir -p /home/$USER \
    && mkdir -p /home/$USER/.composer \
    && if [ "$OS" = "Linux" ]; then \
        groupadd -g $GID $USER; \
        useradd -u $USERID -g $USER $USER -d /home/$USER; \
        chown $USER:$USER /home/$USER; \
        chown -R $USER:$USER /home/$USER/.composer; \
        chown $USER:$USER /var/www; \
    fi \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www
USER $USER




