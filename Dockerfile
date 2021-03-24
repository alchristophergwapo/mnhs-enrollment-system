FROM php:7.3-fpm-alpine
RUN docker-php-ext-install pdo pdo_mysql \
    sockets

EXPOSE 6001

# COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# CMD ["/usr/bin/supervisord"]

# COPY websockets /var/www/html
# RUN chown -R www-data: /var/www/html