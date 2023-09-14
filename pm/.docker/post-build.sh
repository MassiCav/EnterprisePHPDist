#!/bin/bash
set -e

echo "Installing development and building tools..."
apt-get -y update && apt-get -y upgrade
apt-get install -y "php$PHP_VER-zend-dev libssl-dev"

echo "Compiling Swoole from source..."
cd /opt
git clone https://github.com/openswoole/swoole-src.git
cd /opt/swoole-src
git checkout "$SWOOLE_VER"
phpize && \
./configure --enable-openssl --enable-sockets --enable-mysqlnd && \
make && make install
sleep 2

echo "Configuring PHP to load Swoole extension..."
echo 'extension=openswoole.so' > /etc/zendphp/mods-available/swoole.ini
ln -s /etc/zendphp/mods-available/swoole.ini /etc/zendphp/cli/conf.d/30-swoole.ini

echo "Cleaning up the image..."
cd /
apt-get purge -y "php$PHP_VER-zend-dev libssl-dev" && apt-get -y autoremove && apt-get -y clean
rm -rf /tmp/*  /opt/*  /var/lib/apt/lists/* /var/log/apt/* /var/cache/man/*
