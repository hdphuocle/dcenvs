FROM php:7.2.2-apache

RUN apt-get update -y && apt-get install -y \
    git unzip \
    libxml2-dev \
    libjpeg-dev \
    libpng-dev \
    libzip-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libwebp-dev

RUN docker-php-ext-configure gd --with-gd --with-webp-dir --with-jpeg-dir \
    --with-png-dir --with-zlib-dir --with-freetype-dir
    
RUN docker-php-ext-install gd \
    && docker-php-ext-install zip \
    && docker-php-ext-install mysqli \
    && docker-php-ext-install pdo \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install soap \
    && docker-php-ext-install mbstring

RUN a2enmod rewrite \
    && sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

RUN curl -sS https://getcomposer.org/installer | \
    php -- --install-dir=/usr/bin --filename=composer

RUN cd /tmp \
    && curl -sSL https://github.com/OXID-eSales/oxideshop_ce/archive/v6.5.2.tar.gz | tar xz \
    && mv /tmp/oxideshop*/* /var/www/html
    
RUN cd /var/www/html \
    && composer config -g repo.packagist composer https://mirrors.aliyun.com/composer/ \
    && composer -vvv -n install \
    && composer require -n oxid-esales/oxideshop-demodata-ce

RUN chmod -R 777 /var/www
