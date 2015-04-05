#!/bin/sh

DIRECTORY=$(cd "$(dirname "$0")"; pwd)

if [ ! -e "$DIRECTORY/../../config/config.local.php" ]; then
    cp $DIRECTORY/../../config/config.local.php.dist $DIRECTORY/../../config/config.local.php
fi

chmod 777 $DIRECTORY/../../config/config.local.php
chmod 777 $DIRECTORY/../../tmp
chmod 777 $DIRECTORY/../../logs
chmod 777 $DIRECTORY/../../../uploads
chmod 777 $DIRECTORY/../../plugins
