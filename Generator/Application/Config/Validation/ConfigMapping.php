<?php

/*
 * (c) Etienne de Longeaux <etienne.delongeaux@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sfynx\CoreBundle\Generator\Application\Config\Validation;

use Symfony\Component\Yaml\Yaml;

use Sfynx\CoreBundle\Generator\Application\Config\Generalisation\ValidationInterface;
use Sfynx\CoreBundle\Generator\Application\Config\Config;
use Sfynx\CoreBundle\Generator\Application\Config\Exception\ConfigException;
use Sfynx\CoreBundle\Generator\Domain\Widget\WidgetParser;
use Sfynx\CoreBundle\Generator\Domain\Report\Generalisation\AbstractGenerator;
use Sfynx\CoreBundle\Generator\Domain\Component\Config\Loader;

/**
 * Config ConfigMapping
 * @package Sfynx\CoreBundle\Generator\Application\Config\Validation
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class ConfigMapping implements ValidationInterface
{
    /** @var array */
    protected $commandFields = [];
    /** @var array */
    protected static $includeKeys = ['required', 'primaryKey'];

    /**
     * @inheritdoc
     */
    public function validate(Config $config)
    {
        if (!empty($config->get('conf-array')['mapping'])) {
            $mapping = $config->get('conf-array')['mapping'];

            $providers = $this->parseProviders($mapping);
            $entities = $this->parseEntities($mapping);
            $vo = $this->parseValueObjects($mapping);
            $tree = $this->buildTree($entities, $vo);

            $config->set('conf-mapping', [
                'entities' => $entities,
                'valueObjects' => $vo,
                'providers' => $providers,
                'tree' => $tree,
                'commandFields' => AbstractGenerator::transform($this->commandFields, false)
            ]);
        }
    }

    /**
     * Parse Value object
     */
    protected function parseProviders(array $mapping): array
    {
        if (isset($mapping['x-providers'])) {
            return $mapping['x-providers'];
        }

        return [];
    }    

    /**
     * Parse Value object
     */
    protected function parseValueObjects(array $mapping): array
    {
        if (isset($mapping['x-valueObjects'])) {
            return $mapping['x-valueObjects'];
        }

        return [];
    }

    /**
     * Parse entities
     */
    protected function parseEntities(array $mapping): array
    {
        if (isset($mapping['x-entities'])) {
            return $mapping['x-entities'];
        }

        return [];
    }

    /**
     * @param array|null $entities
     * @param array|null $vo
     * @param array $tree
     * @return array
     */
    protected function buildTree(array $entities = null, array $vo = null, array $tree = [], string $nameEntityOverload = ''): array
    {
        foreach ($entities as $nameEntity => $data) {
            foreach ($data['x-fields'] as $nameField => $field) {
                $field['entityName'] = $nameEntity;
                if (!empty($nameEntityOverload)) {
                    $field['entityName'] = $nameEntityOverload;
                }

                if (strtolower($field['type']) == 'valueobject') {
                    $tree[$nameEntity][$nameField] = $this->buildTree([$vo[$field['voName']]], $vo, [], $field['entityName']);

                    foreach($field as $j => $p) {
                        if (in_array($j, self::$includeKeys)) {
                            foreach ($tree[$nameEntity][$nameField] as $k => $f) {
                                $tree[$nameEntity][$nameField][$k][$j] = $field[$j];
                            }
                        }
                    }
                    if (isset($field['mapping'])) {
                        $tree[$nameEntity][$nameField]['id']['mapping'] = $field['mapping'];
                    }

                    if (isset($field['name'])) {
                        $this->addCommandFields($tree[$nameEntity][$nameField], $field['name'], '');
                    } else {
                        $this->addCommandFields($tree[$nameEntity][$nameField], $nameEntity, $nameField);
                    }

                    unset($field['type']); unset($field['voName']);
                    $tree[$nameEntity][$nameField] = array_merge($field, $tree[$nameEntity][$nameField]);
                } else {
                    if (!isset($field['required'])) {
                        $field['required'] = false;
                    }
                    $tree[$nameEntity][$nameField] = $field;

                    $this->addCommandFields([$nameField => $field], $nameEntity);
                }
            }
        }

        if (array_key_exists(0, $tree)) {
            return $tree[0];
        }

        return $tree;
    }

    /**
     * @param array $fields
     * @param string $name
     * @param string $suffix
     * @return bool
     */
    protected function addCommandFields(array $fields, string $name, string $suffix = ''): bool
    {
        if (is_numeric($name)) {
            return false;
        }

        foreach ($fields as $attribut => $field) {
            $name_ = $name;
            if (strtolower($suffix) !== strtolower($name)) {
                $name_ = $name . ucfirst($suffix);
            }

            if (isset($field['name'])) {
                $this->commandFields[] = $field;
            } elseif (isset($field['type'])) {
                str_replace (ucfirst($attribut), '', $name_, $count);
                if (strtolower($attribut) !== strtolower($name_) && $count == 0) {
                    $name_ = $name_ . ucfirst($attribut);
                }
                $field['name'] = $name_;

                $this->commandFields[] = $field;
            } elseif (is_array($field)) {
                foreach ($field as $nameChildren => $v) {
                    if (is_array($v)) {
                        $this->addCommandFields($field, $name_, $nameChildren);
                    }
                }
            }
        }

        return true;
    }
}