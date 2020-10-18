FROM php:7.4-cli AS base

RUN set -eux; \
    apt-get update; \
    apt-get upgrade -y; \
    apt-get install -y \
            ssh \
            git; \
    rm -rf /var/lib/apt/lists/*

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
