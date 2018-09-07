<?php
\chdir(__DIR__ . '/../../');

if (!\file_exists('vendor/autoload.php')) {
    echo '[ERROR] It\'s required to run "composer install" before building Sfynx DDD Generator!' . PHP_EOL;
    exit(1);
}

$filename = 'build/sfynx-ddd-generator.phar';
if (\file_exists($filename)) {
    \unlink($filename);
}

$phar = new \Phar($filename, 0, 'sfynx-ddd-generator.phar');
$phar->setSignatureAlgorithm(\Phar::SHA1);
$phar->startBuffering();

$files = \array_merge(\rglob('*.php'), \rglob('*.js'), \rglob('*.html'), \rglob('*.css'), \rglob('*.png'), \rglob('*.ttf'));
$exclude = '!^(\.git)|(\.svn)|(bin)|([tT]ests)!';
foreach ($files as $file) {
    if (\preg_match($exclude, $file)) {
        continue;
    }
    $path = \str_replace(__DIR__ . '/', '', $file);
    $phar->addFromString($path, \file_get_contents($file));
}

$phar->setStub(<<<STUB
#!/usr/bin/env php
<?php

/*
* This file is part of the Sfynx DDD Generator
*
* (c) Etienne de Longeaux
*
* This source file is subject to the LGPL license that is bundled
* with this source code in the file LICENSE.
* 
* version: v2.9.22
*/

Phar::mapPhar('sfynx-ddd-generator.phar');

require_once 'phar://sfynx-ddd-generator.phar/vendor/autoload.php';

function includeIfExists(\$file)
{
    if (\file_exists(\$file)) {
        return include \$file;
    }
}
\$loader = includeIfExists('/var/www-app/vendor/autoload.php');

(new \Sfynx\CoreBundle\Generator\Application\Application())->run(\$argv);

__HALT_COMPILER();
STUB
);
$phar->stopBuffering();

\chmod($filename, 0755);

function rglob($pattern = '*', $flags = 0, $path = '')
{
    $paths = \glob($path . '*', GLOB_MARK | GLOB_ONLYDIR | GLOB_NOSORT);
    $files = \glob($path . $pattern, $flags);
    foreach ($paths as $path) {
        $files = \array_merge($files, \rglob($pattern, $flags, $path));
    }
    return $files;
}
