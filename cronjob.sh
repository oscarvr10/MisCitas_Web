#!/bin/bash
a2enmod headers
echo "Header set MyHeader \"%D %t"\" >> /etc/apache2/apache2.conf
echo "Header always unset \"X-Powered-By\"" >> /etc/apache2/apache2.conf
echo "Header unset \"X-Powered-By\"" >> /etc/apache2/apache2.conf

# Installing cron and execute cron job
apt-get update -qq && apt-get install cron -yqq
service cron start
(crontab -l 2>&1/dev/null; echo "* * * * * cd /home/site/wwwroot && php artisan schedule:run")|crontab

/usr/sbin/apache2ctl -D FOREGROUND