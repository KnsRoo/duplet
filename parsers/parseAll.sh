#!/bin/sh

SCRIPT_DIR=`dirname $0`;

dir="$SCRIPT_DIR/../Sync"

while inotifywait -qqr -e move -e create -e modify "$dir"; do
    # sleep 1200

    # php $SCRIPT_DIR/parseImages.php;
    # php $SCRIPT_DIR/parseGroups.php;
    php $SCRIPT_DIR/parseProducts.php;
done
