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

echo "Compiling Inotify from source..."
cd /opt
tar -xzf php-inotify-3.0.0.tar.gz
cd ./php-inotify-3.0.0
phpize && \
./configure && \
make && make install
sleep 2

echo "Configuring PHP to load Swoole extension..."
echo 'extension=openswoole.so' > /etc/zendphp/mods-available/swoole.ini
ln -s /etc/zendphp/mods-available/swoole.ini /etc/zendphp/cli/conf.d/30-swoole.ini

echo "Configuring PHP to load INotify extension..."
echo 'extension=inotify.so' > /etc/zendphp/mods-available/inotify.ini
ln -s /etc/zendphp/mods-available/inotify.ini /etc/zendphp/cli/conf.d/20-inotify.ini

echo "Cleaning up the image..."
cd /
apt-get purge -y "php$PHP_VER-zend-dev libssl-dev" && apt-get -y autoremove && apt-get -y clean
rm -rf /tmp/*  /opt/*  /var/lib/apt/lists/* /var/log/apt/* /var/cache/man/*
