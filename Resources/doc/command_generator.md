# DDD Generator Command

## Summary

The following documents are available :

- [Installation](#installation)
- [Usage](#usage)
- [Launch XMI file version 1.2 with Umbrello](#launch-xmi-file-version-1-2-with-umbrello)
- [Launch XMI file version 2.1 with StarUml](#launch-xmi-file-version-2-1-with-struml)

## Installation

> a) Composer

```bash
composer global require 'sfynx-project/core-bundle'
```

Please note that the `~/.composer/vendor/bin` directory must be in your `$PATH`. For example in your `~/.bash_profile` (or `~/.bashrc`), add :

```bash
export PATH=~/.composer/vendor/bin:$PATH
```

> b) Phar

```bash
sudo sh -c "curl -L https://github.com/pigroupe/SfynxCoreBundle/blob/v2.11.3/releases/sfynx-ddd-generator.phar?raw=true > /usr/local/bin/sfynx-ddd-generator"
sudo chmod +rx /usr/local/bin/sfynx-ddd-generator
```

> c) Docker

```bash
docker run --rm \
    --user $(id -u):$(id -g) \
    --volume /local/path:/project \
    sfynx/sfynx-ddd-generator [<options>]
```

## Usage

> a) With one configuration file

```bash
sfynx-ddd-generator \
    sfynx:ddd:generate \
    --namespace=MyContext \
    --conf-file=./Resources/config/generator/models/sfynx-ddd-generator.yml \
    --report-template=default \
    --report-dir=build
```

```bash
sfynx-ddd-generator \
    sfynx:ddd:generate \
    --namespace=Sfynx\\AuthBundle \
    --conf-file=./Resources/config/generator/auth/authbundle_entity_role_api_query.yml \
    --report-template=default \
    --report-dir=build
    -vvv
```

```bash
sfynx-ddd-generator \
    sfynx:ddd:generate \
    --namespace=Sfynx\\AuthBundle \
    --conf-file=./Resources/config/generator/auth/authbundle_entity_user_api_command.yml \
    --report-template=default \
    --report-dir=build
    -vvv
```

> b) With multiple configuration files in directory

**from one repository**
```bash
sfynx-ddd-generator \
    sfynx:ddd:generate \
    ./Resources/config/generator/auth \
    --namespace=Sfynx\\AuthBundle \
    --report-template=default \
    --report-dir=build
```

**from multiple repositories**
```bash
sfynx-ddd-generator \
    sfynx:ddd:generate \
    ./Resources/config/generator/auth,./Resources/config/generator/models \
    --report-template=default \
    --report-dir=build
```

**from repository with multiple import configuration files**
```bash
sfynx-ddd-generator \
    sfynx:ddd:generate \
    ./Resources/config/generator/imports \
    --namespace=MyContext2 \
    --report-template=default \
    --report-dir=build
```

> c) Add XMI generator file report

```bash
sfynx-ddd-generator \
    sfynx:ddd:generate \
    --namespace=MyContext \
    --conf-file=./Resources/config/generator/models/sfynx-ddd-generator.yml \
    --report-xmi="--output=build/MyContext.xmi|--autoload=/var/app/vendor|--recursive|build/MyContext" \
    --report-template=default \
    --report-dir=build
```

**Xmi options register in --report-xmi argument
```
--autoload=folder           Folder for the autoload
--no-private                do not output private methods and attributes
--no-protected              do not output protected methods and attributes
--no-public                 do not output public methods and attributes
--strict                    activate E_STRICT error_reporting
--help                      shows this help
--recursive                 look for .php files in specified directories
--output=<output.xmi>       select xmi output file
```

> d) Docker utilisation

```bash
docker run --rm \
--volume $PWD:/var/www \
sfynxdevops/generator \
sfynx-ddd-generator \
    sfynx:ddd:generate \
    --namespace=Sfynx\\AuthBundle \
    --conf-file=/var/www/Resources/config/generator/auth/authbundle_entity_role_api.yml \
    --report-template=default \
    --report-dir=/var/www/build
```

```bash
docker run --rm \
--volume $PWD:/var/www \
--volume /var/www/cmf-sfynx/www:/var/app \
sfynxdevops/generator \
sfynx-ddd-generator \
    sfynx:ddd:generate \
    --namespace=MyContext \
    --conf-file=/var/www/Resources/config/generator/models/sfynx-ddd-generator.yml \
    --report-xmi="--output=/var/www/build/MyContext.xmi|--autoload=/var/app/vendor|--recursive|/var/www/build/MyContext" \
    --report-template=default \
    --report-dir=/var/www/build
```

> e) Using the docker with XMI conversion from version 1.2 to 2.1

```bash
docker run --rm \
-e XMI_FILE_PATH=/var/www/build/MyContext \
--volume $PWD:/var/www \
--volume /var/www/cmf-sfynx/www:/var/app \
sfynxdevops/generator \
sfynx-ddd-generator \
    sfynx:ddd:generate \
    --namespace=MyContext \
    --conf-file=/var/www/Resources/config/generator/models/sfynx-ddd-generator.yml \
    --report-xmi="--output=/var/www/build/MyContext.xmi|--autoload=/var/app/vendor|--recursive|/var/www/build/MyContext" \
    --report-template=default \
    --report-dir=/var/www/build
```

## Launch XMI file version 1.2 with Umbrello

Execute it with Doker like this:

```bash
docker run --rm -d -u 1000:1000 \
   -e HOME \
   -e DISPLAY=unix:0 \
   -e XAUTHORITY=/tmp/xauth \
   -v $XAUTHORITY:/tmp/xauth -v $HOME:$HOME \
   -v /etc/passwd:/etc/passwd:ro -v /etc/group:/etc/group:ro \
   -v /tmp/.X11-unix:/tmp/.X11-unix \
   -v $HOME/Documents:/root/Documents:rw \
   -v $HOME/.config/umbrello:/root/.config:rw \
   --name umbrello \
   mrorgues/umbrello
```

After import <XmiFile>.xmi file generate from command

## Launch XMI file version 2.1 with StarUml

Execute it with Doker like this

```bash
docker run -it --rm -u 1000:1000 \
   -e HOME \
   -e DISPLAY=unix:0 \
   -e XAUTHORITY=/tmp/xauth \
   -v $XAUTHORITY:/tmp/xauth -v $HOME:$HOME \
   -v /etc/passwd:/etc/passwd:ro -v /etc/group:/etc/group:ro \
   -v /tmp/.X11-unix:/tmp/.X11-unix \
   -v "$(pwd)":/starUML \
   -w /starUML \
   --name staruml \
   jamesmstone/staruml "$@"
```

After:
1) ceate a new project: File > New
2) Import <XmiFile>_v2.1.xmi file generate from command: File > Import > XMI Import (v2.1)
