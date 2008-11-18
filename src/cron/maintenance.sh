#!/bin/sh

cd /var/www/phplogcon/cron/
php ./maintenance.php cleardata 2 olderthan 86400
