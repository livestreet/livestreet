#!/bin/sh

mysql -u root -e 'CREATE DATABASE social_test;'
mysql -u root -B social_test < ./tests/fixtures/sql/sql.sql
mysql -u root -B social_test < ./tests/fixtures/sql/geo_base.sql
mysql -u root -B social_test < ./tests/fixtures/sql/patch.sql
