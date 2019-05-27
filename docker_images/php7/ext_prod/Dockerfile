FROM rudiwork/php7:base

ENV PHP_OPCACHE_ENABLE ${PHP_OPCACHE_ENABLE:-0}
ENV PHP_OPCACHE_CLI_ENABLE ${PHP_OPCACHE_CLI_ENABLE:-0}

RUN set -ex \
  && apk add --no-cache --update zlib \
  && apk --no-cache add \
     postgresql-dev \
     autoconf \ 
     bzip2 \
     re2c \ 
     file \ 
     make \
     g++ \
     freetype-dev \
     libjpeg-turbo-dev \
     libmcrypt-dev \
     libpng-dev \
     libmemcached-dev \
     git \
     openldap-dev \
     openssh \
     imap-dev \
     libxml2-dev \
     libssh2-dev \
     && pecl install memcached \
     && pecl install ssh2-1.1.2 \
     && docker-php-ext-enable memcached \
     && docker-php-ext-enable ssh2 \
     && docker-php-ext-install pdo pdo_pgsql opcache exif bcmath pdo_mysql ldap \
     && docker-php-ext-install -j$(nproc) iconv \
     && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
     && docker-php-ext-install -j$(nproc) gd \
     && docker-php-ext-install -j$(nproc) zip \
     && docker-php-ext-configure imap --with-imap --with-imap-ssl \
     && docker-php-ext-install imap soap 

RUN cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini

COPY ./opcache.ini /usr/local/etc/php/conf.d/opcache.ini
COPY ./prod.ini /usr/local/etc/php/conf.d/prod.ini
COPY ./fpm.conf /usr/local/etc/php-fpm.d/x.fpm.conf

WORKDIR /app
