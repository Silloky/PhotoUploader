#!/bin/sh

cd /media
useradd -ms /bin/bash photouploader
groupadd -g 1002 gallery-access
usermod -a -G gallery-access photouploader
chown -R photouploader:gallery-access /media
chmod -R 774 /media
apache2-foreground