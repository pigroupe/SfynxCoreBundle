# Contribute

In order to run unit tests, please install the dev dependencies:

    curl -sS https://getcomposer.org/installer | php
    php composer.phar install

Then, in order to run the test suite:

    ./vendor/bin/phpunit

Thanks for your help !

## Releasing

Please NEVER tag manually.

First, changes sources according new tag:

    make tag <VERSION=(major|minor|patch)>
    
version can be `major`, `minor` or `patch`

Then create release and Git tag with

    make release

## Profiler from kcachegring with docker

```bash
docker run -it --rm -u 1000:1000 \
   -e HOME \
   -e DISPLAY=unix:0 \
   -e XAUTHORITY=/tmp/xauth \
   -v $XAUTHORITY:/tmp/xauth -v $HOME:$HOME \
   -v /etc/passwd:/etc/passwd:ro -v /etc/group:/etc/group:ro \
   -v /tmp/.X11-unix:/tmp/.X11-unix \
   --name kcachegrind \
    quetzacoalt/kcachegrind
```