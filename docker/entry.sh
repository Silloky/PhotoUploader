#!/bin/sh

cd /media

useradd -ms /bin/bash photouploader
groupadd -g 1002 gallery-access
usermod -a -G gallery-access photouploader

chown -R photouploader:gallery-access /media
chmod -R 774 /media

# reallysimplejwt's requirements for a JWT secret
letters=$(head /dev/urandom | tr -dc 'A-Za-z0-9' | head -c11)
special=$(head /dev/urandom | tr -dc '*&!@%^#$' | head -c1)
export JWT_KEY=$letters$special

apache2-foreground