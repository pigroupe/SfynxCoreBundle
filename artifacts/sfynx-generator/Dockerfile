FROM php:7.2-fpm-alpine3.7

LABEL Etienne de Longeaux <etienne.delongeaux@gmail.com>

#
# ARGS
#
ARG ROOT_PATH
ARG UID=33
ARG GID=33

#
# INSTALL TOOLS
# apk info -R <package> => get dependancies
#
RUN apk add --no-cache --virtual .packages \
        bash \
        graphviz \
        libxslt \
        libxml2 && \
    apk add --no-cache --virtual .dev-tools \
        make \
        git \
        libxml2-dev \
        libxslt-dev \
        curl && \
#
# INSTALL PHP EXTENSION
# php --ri gd
#
#
    docker-php-ext-install dom  && \
    docker-php-ext-install xsl && \
#
# INSTALL PHP PEAR
    apk add --no-cache --virtual .packages \
        php7-pear && \

#
# BUILD SFYNX DDD GENERATOR PHAR
#
#    curl -L https://github.com/pigroupe/SfynxCoreBundle/blob/${SFYNX_DDD_GENERATOR}/releases/sfynx-ddd-generator.phar?raw=true > /usr/local/bin/sfynx-ddd-generator && \
#    chmod +x /usr/local/bin/sfynx-ddd-generator && \
#    curl -s https://getcomposer.org/installer | php && \
#    mv composer.phar /usr/local/bin/composer && \
#    echo 'phar.readonly=0' > /usr/local/etc/php/conf.d/docker-php-ext-phar.ini && \
#    git clone --branch ${SFYNX_DDD_GENERATOR}  https://github.com/pigroupe/SfynxCoreBundle.git && \
#
#    cd SfynxCoreBundle && make build && cd .. && \
#    rm -rf SfynxCoreBundle && \
#    rm /usr/local/bin/composer && \

#
# ADD PEAR PHP_UML
#
    pear install PHP_UML && \
#
# CLEAN UP
#
    apk del --purge .dev-tools && \
    rm -rf /var/lib/apt/lists/* && \
    rm -rf /var/cache/apk/* && \
    rm -fr /tmp/*

#
# ARGS
#
ENV TERM xterm
ENV SFYNX_DDD_GENERATOR 'v2.10.2'

#
# ADD SFYNX DDD GENERATOR
#
ADD https://github.com/pigroupe/SfynxCoreBundle/blob/${SFYNX_DDD_GENERATOR}/releases/sfynx-ddd-generator.phar?raw=true /usr/local/bin/sfynx-ddd-generator
#COPY sfynx-ddd-generator.phar /usr/local/bin/sfynx-ddd-generator
RUN chmod +rx /usr/local/bin/sfynx-ddd-generator

#
# PREPARE MAP USER ID
#
RUN mkdir -p /var/www
RUN set -x; \
    mkdir -p /home/www-data && \
#    addgroup -g 1000 -S www-data && \
#    adduser -u 1000 -D -S -G www-data www-data -h /home/www-data -s /bin/bash www-data && \
    chown -Rf www-data:www-data \
        /var/www

COPY $ROOT_PATH/mapid.sh /tmp/mapid.sh
RUN \
    . /tmp/mapid.sh && \
    if [ ! -z "${UID}" ]; then map_uid www-data ${UID}; fi && \
    if [ ! -z "${GID}" ]; then map_gid www-data ${GID}; fi && \
    add_wwwdata_to_33

#
# ENTRYPOINT
#
COPY $ROOT_PATH/docker-entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh
RUN rm -rf /var/www/html

USER www-data

WORKDIR /var/www

ENTRYPOINT ["/entrypoint.sh"]

CMD ["sfynx-ddd-generator", "--version"]