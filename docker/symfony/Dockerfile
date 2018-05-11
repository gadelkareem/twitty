FROM php:7.0-cli
WORKDIR "/app"

# Fix debconf warnings upon build
ARG DEBIAN_FRONTEND=noninteractive

# Install selected extensions and other stuff
RUN apt-get update \
    && apt-get -y --no-install-recommends install php-memcached \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

# Install git
RUN apt-get update \
    && apt-get -y install git zip unzip \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*


RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin
RUN mv /usr/bin/composer.phar /usr/bin/composer
RUN composer install --no-dev
RUN composer install dump-autoload --optimize --no-dev --classmap-authoritative


ENTRYPOINT /bin/bash


