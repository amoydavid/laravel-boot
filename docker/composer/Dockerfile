FROM php:8.1-cli-alpine

LABEL maintainer="Liu Wei"

WORKDIR /var/www/html

ENV TZ=UTC
ENV ENV="/root/.ashrc"
ENV DEBIAN_FRONTEND noninteractive

RUN set -eux && sed -i 's/dl-cdn.alpinelinux.org/mirrors.aliyun.com/g' /etc/apk/repositories

ADD https://gitee.com/amoydavid/docker-php-extension-installer/releases/download/v2.0.5/install-php-extensions /usr/local/bin/

RUN set -eux; \
    chmod +x /usr/local/bin/install-php-extensions \
    # Install persistent / runtime deps
    && apk --update add --no-cache tzdata gnupg su-exec zip unzip git supervisor sqlite libcap \
    # Install PHP extensions
    && install-php-extensions pcntl ldap redis intl soap imap pdo_mysql pcov msgpack bcmath igbinary gd zip opcache @composer

# Clean up
RUN rm -rf /usr/share/php /usr/share/php8 /usr/src/* /usr/local/bin/phpdbg \
        /usr/local/bin/install-php-extensions /var/lib/apt/lists/* /tmp/* /var/tmp/* \
    # Miscellany
    && setcap "cap_net_bind_service=+ep" /usr/local/bin/php \
    && ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone \
    && echo http://dl-2.alpinelinux.org/alpine/edge/community/ >> /etc/apk/repositories \
    && apk --no-cache add shadow
