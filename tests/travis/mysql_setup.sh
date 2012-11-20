#!/bin/sh

sudo apt-get install mysql-server mysql-client
mysql -u root -e 'CREATE DATABASE social_test;'
mysql -u root -B social_test < ./tests/fixtures/sql/install_base.sql
mysql -u root -B social_test < ./tests/fixtures/sql/geo_base.sql
