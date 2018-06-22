<?php
namespace Sfynx\CoreBundle\Generator\Domain\Templater\Templater_\Architecture\Application\Validation\Type;

use stdClass;
use Exception;
use Sfynx\CoreBundle\Generator\Domain\Component\File\ClassHandler;
use Sfynx\CoreBundle\Generator\Domain\Component\Table\Table;
use Sfynx\CoreBundle\Generator\Domain\Templater\Templater_\Architecture\Application\Validation\Type\Extension;
use Sfynx\CoreBundle\Generator\Domain\Widget\Generalisation\Interfaces\WidgetInterface;
use Sfynx\CoreBundle\Generator\Domain\Templater\Generalisation\Interfaces\ExtensionInterface;
use Sfynx\CoreBundle\Generator\Domain\Templater\Generalisation\Interfaces\TemplaterInterface;
use Sfynx\CoreBundle\Generator\Domain\Templater\Generalisation\AbstractTemplater;

/**
 * @category   Sfynx\CoreBundle\Generator
 * @package    Domain
 * @subpackage TemplaterTemplater_\Architecture\Application\Validation\Type
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class Templater extends AbstractTemplater implements TemplaterInterface
{
    /** @var ExtensionInterface */
    protected $extensionInstance;
    /** @var string */
    const TAG = 'templater_archi_app_validation_type';
    /** @var bool */
    const NAMESPACE_WITH_CQRS = false;

    /**
     * List of concrete extension Type that can be built using this form type.
     * @var string[]
     */
    protected static $extensionList = [
        ClassHandler::TYPE_ENTITY => Extension\EntityTypeExtension::class,
        ClassHandler::TYPE_TEXT => Extension\TextTypeExtension::class,
        ClassHandler::TYPE_ARRAY => Extension\ChoiceTypeExtension::class,
        ClassHandler::TYPE_TEXTAREA => Extension\TextareaTypeExtension::class,
        ClassHandler::TYPE_DATE => Extension\DateTypeExtension::class,
        ClassHandler::TYPE_SUBMIT => Extension\SubmitTypeExtension::class,
    ];

    /** @var array */
    protected static $excludeAttributs = [
        'properties',
        'mapping',
        'primaryKey',
        'foreignKey',
        'defaultValue',
        'nullable',
        'entityName',
        'form',
    ];

    /**
     * @inheritdoc
     */
    public static function scriptList(string $template): array
    {
        return [
            '0' => [
                'Application\Validation\Type',
                __DIR__ . "/Resources/${template}/FormType.php",
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        return 'Form type class';
    }

    /**
     * @inheritdoc
     */
    public function getCategory(): string
    {
        return WidgetInterface::CAT_ARCHI_APP;
    }

    /**
     * @inheritdoc
     */
    public function getTag(): string
    {
        return self::TAG;
    }

    /**
     * @inheritdoc
     */
    public function getDescription(): string
    {
        return <<<EOT
This class expose form type component
EOT;
    }

    /**
     * @param string $child
     * @param string $type
     * @param stdClass|null $options
     * @return string
     */
    public function add(string $child, string $type, stdClass $options = null, bool $isEndLine = false): string
    {
        $content = $this->_add($child, $type, $options, $isEndLine). PHP_EOL;
        if (ClassHandler::TYPE_ENTITY == $type) {
            $content .= '/*        ' . PHP_EOL;
            $content .= '        ' . $this->_add($child, ClassHandler::TYPE_ARRAY, $options, $isEndLine) . '*/' . PHP_EOL;
        }

        return $content;
    }

    /**
     * @param string $child
     * @param string $type
     * @param stdClass|null $options
     * @return string
     */
    protected function _add(string $child, string $type, stdClass $options = null, bool $isEndLine = false): string
    {
        // we convert options to array
        if (null === $options) {
            $options = [];
        }
        $options = json_decode(json_encode($options), true);

        if (!empty($options['form']['type'] )) {
            $type = $options['form']['type'];
        }

        // we cheick extension instance
        $instanceValue = self::$extensionList[ClassHandler::TYPE_TEXT];
        if (array_key_exists($type, self::$extensionList)) {
            $instanceValue = self::$extensionList[$type];
        }

        $resolverParametersOption = array_merge($options, ['templater' => $this]);

        // we delete all attributes that are not used by extensions
        foreach (self::$excludeAttributs as $attribut) {
            if (isset($options[$attribut])) {
                unset($options[$attribut]);
            }
        }

        // we execute extension
        $this->extensionInstance = new $instanceValue($options);
        $parameters = $this->extensionInstance->getResolverParameters(false, $resolverParametersOption);

        $type = $this->extensionInstance->getClassExtention()->name;
        if (!empty($resolverParametersOption['form']['serviceType'])) {
            $type = "'" . $resolverParametersOption['form']['serviceType'] . "'";
        }

        $content = sprintf('->add(\'%s\', %s, [', $child, $type) . PHP_EOL;
        foreach ($parameters as $k => $v) {
            if (is_bool($v)) {
                $v = ($v === true) ? 'true' : $v;
                $v = ($v === false) ? 'false' : $v;
                $content .= "            '$k' => $v," . PHP_EOL;
            } elseif (!is_array($v)
                && (
                    strpos($v, 'function') !== false
                    || strpos($v, 'List') !== false
                    || strpos($v, '::class') !== false
                )
            ) {
                $content .= "            '$k' => $v," . PHP_EOL;
            } elseif (is_array($v)) {
                $content .= "            '$k' => []," . PHP_EOL;
            } else {
                $content .= "            '$k' => '$v'," . PHP_EOL;
            }
        }

        $content .= '        ])';
        if ($isEndLine) {
            $content .= '        ]);';
        }

        return $content;
    }
}
