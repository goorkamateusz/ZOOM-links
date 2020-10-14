#!/bin/sh

$path="`dirname $0`"

php "$path/../check-mailbox.php" > "$path/../last_update_output.html"