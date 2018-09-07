# Installation

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
curl https://github.com/pigroupe/SfynxCoreBundle/blob/v2.9.22/releases/sfynx-ddd-generator.phar > /usr/local/bin/sfynx-ddd-generator
chmod +x /usr/local/bin/sfynx-ddd-generator
```

> c) Docker

```bash
docker run --rm \
    --user $(id -u):$(id -g) \
    --volume /local/path:/project \
    sfynx/sfynx-ddd-generator [<options>]
```

# Utilisation

> a) With one configuration file

```bash
sfynx-ddd-generator \
--namespace=MyContext \
--conf-file=./Resources/config/generator/models/sfynx-ddd-generator.yml \
--report-template=default \
--report-dir=build/MyContext
```

> b) With multiple configuration files in directory

```bash
sfynx-ddd-generator \
--namespace=MyContext \
--conf-dir=./Resources/config/generator/models \
--report-template=default \
--report-dir=build/MyContext
```

> c) Add XMI generator file report

```bash
sfynx-ddd-generator \
--namespace=MyContext \
--conf-file=./Resources/config/generator/models/sfynx-ddd-generator.yml \
--report-xmi="--output=build/MyContext.xmi --autoload=/var/www/alterway_symfony/pic/pic-ui/pi/cmf-sfynx/www/vendor --recursive build/MyContext" \
--report-template=default \
--report-dir=build/MyContext
```

> d) Docker utilisation

```bash
docker run --rm \
--volume $PWD:/var/www \
--volume /var/www/alterway_symfony/pic/pic-ui/pi/cmf-sfynx/www:/var/app \
sfynx/generator \
sfynx-ddd-generator \
    --namespace=MyContext \
    --conf-file=/var/www/Resources/config/generator/models/sfynx-ddd-generator.yml \
    --report-xmi="--output=/var/www/build/MyContext.xmi --autoload=/var/app/vendor --recursive /var/www/build/MyContext" \
    --report-template=default \
    --report-dir=/var/www/build/MyContext
```

