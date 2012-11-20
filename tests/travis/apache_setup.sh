#!/bin/sh

sudo apt-get install apache2 libapache2-mod-php5 curl
sudo a2enmod rewrite
echo "$(curl -fsSL https://raw.github.com/stfalcon-studio/livestreet/master/tests/travis/configs/apache_vhost)" | sed -e "s,PATH,`pwd`,g" | sudo tee -a /etc/apache2/sites-available/default > /dev/null
echo "$(curl -fsSL https://raw.github.com/stfalcon-studio/livestreet/master/tests/travis/configs/hosts)" | sudo tee -a /etc/hosts > /dev/null
sudo service apache2 restart
