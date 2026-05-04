# Build from repository root (--context dir that contains docker/, src/, tests/):
#   docker build -f docker/Dockerfile .
#
# Final image embeds php app + baked samplelogs; CI/dev/e2e may still bind-mount ../src.

FROM php:8.2-cli AS loganalyzer-runtime

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        libpng-dev libjpeg62-turbo-dev libfreetype6-dev libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) mysqli pdo_mysql gd zip \
    && rm -rf /var/lib/apt/lists/*

COPY docker/entrypoint.sh /entrypoint.sh
COPY docker/seed-database.php /usr/local/share/loganalyzer/seed-database.php
COPY docker/write-config.php /usr/local/share/loganalyzer/write-config.php
COPY docker/env-disk-sources.php /usr/local/share/loganalyzer/env-disk-sources.php
RUN sed -i 's/\r$//' /entrypoint.sh && chmod +x /entrypoint.sh

WORKDIR /var/www/html
EXPOSE 8080

FROM loganalyzer-runtime AS loganalyzer-web

COPY src/ /var/www/html/
COPY tests/fixtures/samplelogs /samplelogs

ENTRYPOINT ["/entrypoint.sh"]
