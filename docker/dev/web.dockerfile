FROM nginx

ADD ./docker/dev/web.vhost.conf /etc/nginx/conf.d/default.conf

WORKDIR /var/www

EXPOSE 80
