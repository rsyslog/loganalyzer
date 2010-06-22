#!/bin/sh

cd /var/www/phplogcon/cron/
php ./maintenance.php cleandata 2 olderthan 86400
