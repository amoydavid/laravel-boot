#!/bin/bash
docker run --rm --interactive --tty --volume $PWD:/var/www/html --volume ${COMPOSER_HOME:-$HOME/.composer}:/tmp amoydavid/php-cli-composer:8.1-alpine composer $*
