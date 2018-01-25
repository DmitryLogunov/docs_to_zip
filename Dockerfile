FROM php:7.1-fpm
COPY . /usr/src/app
WORKDIR /usr/src/app
CMD [ "php-fpm" ]
