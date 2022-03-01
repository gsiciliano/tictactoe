FROM nginx

ADD ./docker/prod/web.vhost.conf /etc/nginx/conf.d/default.conf

EXPOSE 80
