FROM php:8.2-apache

RUN docker-php-ext-install mysqli pdo pdo_mysql && a2enmod rewrite

WORKDIR /var/www/html

COPY . /var/www/html/

COPY start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

EXPOSE 80 10000

CMD ["/usr/local/bin/start.sh"]