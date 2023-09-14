ARG OS_VERSION=11
ARG ZENDPHP_VERSION=8.2
ARG BASE_IMAGE=cli
FROM cr.zend.com/zendphp/${ZENDPHP_VERSION}:debian-${OS_VERSION}-${BASE_IMAGE} as x86-64
FROM cr.zend.com/zendphp/${ZENDPHP_VERSION}:debian-${OS_VERSION}-${BASE_IMAGE}-arm64 as arm64
LABEL maintainer="Massimiliano Cavicchioli <mcavicchioli@perforce.com>" version="0.1" license="Unlicensed" app.name="DAL (Data Abstraction Layer)"

# Customizations
ARG TIMEZONE=UTC
ARG INSTALL_COMPOSER=true
ARG ZEND_PROFILE=development
ARG SYSTEM_PACKAGES
ARG ZEND_EXTENSIONS_LIST
ARG PECL_EXTENSIONS_LIST
ARG POST_BUILD_BASH
ARG SWOOLE_VERSION

## Prepare the build process
ENV TZ=${TIMEZONE}
ENV PHP_VER=${ZENDPHP_VERSION}
ENV SWOOLE_VER=${SWOOLE_VERSION}
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && \
    echo $TZ > /etc/timezone && \
    mkdir -p /dal

WORKDIR /pm

## Customize PHP runtime according
## to the given building arguments
COPY .docker/post-build.sh /usr/local/sbin/post-build.sh
RUN ZendPHPCustomizeWithBuildArgs.sh

## Set up application
COPY ./application/ /dal/
RUN composer install

## Clean up
RUN rm -rf /var/lib/apt/lists/* && \
    rm -rf /var/cache/* && \
    rm -rf /var/log/* 

CMD [ "php", "/pm/pm.php" ]
