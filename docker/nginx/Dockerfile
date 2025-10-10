FROM nginx:alpine

COPY vhost.conf /etc/nginx/conf.d/default.conf
COPY ./certs /etc/nginx/certs

EXPOSE 80
EXPOSE 443

