#!/bin/sh

cd `dirname "$0"`
cd ..

php check-mailbox.php > last_update.html