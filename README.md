# Core SFYNX Bundle

[![Latest Stable Version](https://img.shields.io/packagist/v/sfynx-project/core-bundle.svg?style=flat-square)](https://packagist.org/packages/bartlett/phpunit-loggertestlistener)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.2-8892BF.svg?style=flat-square)](https://php.net/)
[![License](https://img.shields.io/badge/license-LGPL-red.svg?style=flat-square)](LICENSE)

The Symfony provides a flexible framework that allows you to compose with a DDD (Domain-driven design) applicative architecture.
This is to simplify the work of developers with DDD pattern, and enable to follow the SOLID principles in the POO development.

So, if you need to work with an approach to software development for complex needs by connecting the implementation to an evolving mode, then you're in the right place.

## Documentation

The source of the documentation is stored in the `Resources/doc/` folder in this bundle :

* [Read the Documentation for master](https://github.com/pigroupe/SfynxCoreBundle/blob/master/Resources/doc/index.md)

* [Read the Documentation](Resources/doc/index.md)

###### a) Prerequisites

This version of the bundle requires php 7.2+.

###### b) Configuration

Add dependencies in your `composer.json` file:

```json
"require": {
    ...
    "sfynx-project/core-bundle": "dev-master"
},
```

Install these new dependencies of your application:

```sh
$ composer update --no-interaction --with-dependencies
```

Enable bundles in your application kernel:

```php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = [
        // ...
        new Sfynx\CoreBundle\SfynxCoreBundle(),
    ];
}
```

## Reporting an issue or a feature request

Issues and feature requests are tracked in the [Github issue tracker](https://github.com/pigroupe/SfynxCoreBundle/issues).

When reporting a bug, it may be a good idea to reproduce it in a basic project
built using the [Symfony Standard Edition](https://github.com/symfony/symfony-standard)
to allow developers of the bundle to reproduce the issue by simply cloning it
and following some steps.

## License

**Copyright © 20012-2018, contact@pi-groupe.net.**
**This bundle is under the [GNU Lesser General Public License](LICENSE), permitting combination and redistribution with software that uses the MIT License**

SFYNX is a free software distributed under the LGPL license. This license guarantees the following freedoms:

```
- the freedom to install and use SFYNX for any usage whatsoever;
- the freedom to look into SFYNX’s code and adapt it to your own needs by modifying the source code, to which you have direct access since SFYNX is entirely developed in PHP;
- the freedom to distribute copies of the software to anyone, provided you do not modify or delete the license;
- the freedom to enhance SFYNX and to distribute your enhancements among the public so that the entire community may benefit from it, provided you do not modify or delete the license.
```

- This application is a free software; you can distribute it and/or modify it according to the terms of the GNU Lesser General Public License, as published by the Free Software Foundation; version 2 or (upon your choice) any later version.

- This software is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; not even the implicit warranty for COMMERCIALISATION or CUSTOMISATION FOR A PARTICULAR PURPOSE. For more details, refer to the GNU Lesser General Public License.

- A copy of the GNU Lesser General Public License must be provided with this software; if it is not, please write to the Free Software Foundation Inc., 675 Mass Ave, Cambridge, MA 02139, USA.

- You can download this software from http://pigroupe.github.io/cmf-sfynx/; you will also find a complete user manual and additional information on this site.

- In French law, SFYNX falls under the regulations stipulated in the code of intellectual property rights (CPI). The SFYNX kernel is a collaborative work by its authors, listed above as per article L 113-1 of the CPI. The entire SFYNX project is comprised of a collective work in respect of articles L 113-2 and L 113-5 of the CPI. The authors release the work to the public in accordance with the rights and obligations as defined by the GNU public license.

## About

SfynxCoreBundle is a [Project PI-GROUPE Development](https://github.com/pigroupe) initiative.
See also the list of [contributors](https://github.com/orgs/pigroupe/people).

**For more information** : 
* http://www.sfynx.org
* http://www.pi-groupe.net
