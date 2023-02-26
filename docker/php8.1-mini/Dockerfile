FROM amoydavid/php-cli-composer:8.1-alpine

LABEL maintainer="Liu Wei"

WORKDIR /var/www/html

ARG WWWUSER

ENV TZ=UTC
ENV ENV="/root/.ashrc"
ENV DEBIAN_FRONTEND noninteractive

COPY .ashrc /root/.ashrc
COPY php.ini /usr/local/etc/php/conf.d/99-sail.ini
COPY start-container /usr/local/bin/start-container
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

RUN adduser --disabled-password --gecos "" -u 1337 -s /bin/sh -G www-data sail \
    && ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone \
    && chmod +x /usr/local/bin/start-container \
    && echo http://dl-2.alpinelinux.org/alpine/edge/community/ >> /etc/apk/repositories \
    && apk --no-cache add shadow

ENTRYPOINT ["start-container"]
