#!/bin/bash

echo 'Running migrates'
php artisan migrate --force

echo 'Clearing and configuring cache'
php artisan optimize

/usr/bin/supervisord -n -c /etc/supervisor/conf.d/supervisord.conf
/usr/bin/supervisorctl -n -c /etc/supervisor/conf.d/supervisord.conf
