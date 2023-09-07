FROM php:8.1-fpm-alpine

###> recipes ###
###< recipes ###

# install some php extensions
RUN apk update && apk upgrade \
    && docker-php-ext-install \
       exif \
       mysqli \
       pdo \
       pdo_mysql \
    && docker-php-ext-install opcache

RUN apk add bash
    
# install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer &&\
    chmod +x /usr/local/bin/composer

# Symfony CLI
RUN curl -sS https://get.symfony.com/cli/installer | bash && mv /root/.symfony5/bin/symfony /usr/local/bin/symfony

# WORK DIR
COPY ./app/ /var/www/html
WORKDIR /var/www/html

# install node.js
RUN apk update && apk upgrade && apk add --update nodejs npm

RUN composer update
RUN composer install

RUN npm install
RUN npm run dev

CMD ["/usr/local/bin/symfony", "local:server:start" , "--port=8000", "--no-tls"]