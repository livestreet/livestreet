#!/bin/sh

ABSOLUTE_FILENAME=`readlink -e "$0"`
DIRECTORY=`dirname "$ABSOLUTE_FILENAME"`

if [ ! -e "$DIRECTORY/../application/config/config.local.php" ]; then
    cp $DIRECTORY/../application/config/config.local.php.dist $DIRECTORY/../application/config/config.local.php
fi

chmod 777 $DIRECTORY/../application/config/config.local.php
chmod 777 $DIRECTORY/../application/tmp
chmod 777 $DIRECTORY/../application/logs
chmod 777 $DIRECTORY/../uploads
chmod 777 $DIRECTORY/../application/plugins
