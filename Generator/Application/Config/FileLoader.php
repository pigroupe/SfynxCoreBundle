<?php

namespace Sfynx\CoreBundle\Generator\Application\Config;

use Symfony\Component\Yaml\Yaml;

class FileLoader extends \Symfony\Component\Config\Loader\FileLoader
{
    public function load($resource, $type = null)
    {
        $resource = $this->getLocator()->locate($resource, null, true);
        $content = Yaml::parseFile($resource);

        if (isset($content['imports'])) {
            foreach ($content['imports'] as $import) {
                $extraContent = $this->import($import['resource']);
                $content = array_replace_recursive($extraContent, $content);
            }
        }

        return $content;
    }

    public function supports($resource, $type = null)
    {
        return is_string($resource) && 'yml' === pathinfo($resource, PATHINFO_EXTENSION);
    }
}
