#!/bin/sh

# test -e composer.phar || curl -O https://getcomposer.org/composer.phar

php -S ${DOCKER_HOST:-[::1]}:${DOCKER_PORT:-1080} -d error_reporting=E_ALL -d display_errors=1 -t src/
