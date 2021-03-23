FROM php:7.3-fpm-alpine
RUN docker-php-ext-install pdo pdo_mysql \
    sockets

CMD ["php", "artisan", "websocket:serve"]

RUN apk update

# Cleanup
# apk del .build-deps && \
RUN rm -rf /var/cache/apk/* && \
    rm -rf /tmp/*

RUN mkdir -p /var/www

USER www-data

WORKDIR /var/www/html

EXPOSE 6001

CMD ["php", "artisan", "websockets:serve"]