# LogAnalyzer local dev / CI (PHP + extensions for web UI, charts, MySQL)
FROM php:8.2-cli

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        libpng-dev libjpeg62-turbo-dev libfreetype6-dev libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) mysqli pdo_mysql gd zip \
    && rm -rf /var/lib/apt/lists/*

COPY entrypoint.sh /entrypoint.sh
COPY seed-database.php /usr/local/share/loganalyzer/seed-database.php
COPY write-config.php /usr/local/share/loganalyzer/write-config.php
# Windows checkouts may use CRLF; bash needs LF
RUN sed -i 's/\r$//' /entrypoint.sh && chmod +x /entrypoint.sh

WORKDIR /var/www/html
EXPOSE 8080
ENTRYPOINT ["/entrypoint.sh"]
