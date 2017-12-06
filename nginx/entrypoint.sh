#!/bin/bash

su -s /bin/bash apache -c '/var/www/vendor/bin/magium-configuration build'

mkdir /var/www/var && chown apache /var/www/var

php-fpm

nginx -g "daemon off;"
