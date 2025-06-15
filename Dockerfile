FROM php:8.2-apache

RUN docker-php-ext-install pdo pdo_mysql

RUN a2enmod rewrite
RUN a2enmod ssl

RUN mkdir /etc/apache2/ssl && \
    openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
      -keyout /etc/apache2/ssl/apache.key \
      -out /etc/apache2/ssl/apache.crt \
      -subj "/C=BR/ST=SP/L=City/O=Org/OU=IT Department/CN=localhost"

COPY ssl.conf /etc/apache2/sites-available/default-ssl.conf

RUN a2ensite default-ssl
EXPOSE 443


ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/000-default.conf

RUN mkdir -p /var/www/html/public

COPY . /var/www/html

#RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN addgroup --gid 1000 app && adduser --uid 1000 --gid 1000 --disabled-password --gecos '' app

WORKDIR /var/www/html

RUN composer install --no-dev --optimize-autoloader

RUN apt-get update && apt-get install -y curl certbot python3-certbot-apache

COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

ENTRYPOINT ["/entrypoint.sh"]
