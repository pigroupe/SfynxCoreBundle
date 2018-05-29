<?php
namespace Sfynx\CoreBundle\Generator\Domain\Templater\Templater_\Architecture\Application\Validation\Type;

use stdClass;
use Exception;
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

    const TYPE_ENTITY = 'id';
    const TYPE_TEXT = 'string';
    const TYPE_TEXTAREA = 'textarea';
    const TYPE_DATE = 'datetime';
    const TYPE_SUBMIT = 'submit';

    /**
     * List of concrete extension Type that can be built using this form type.
     * @var string[]
     */
    protected static $extensionList = [
        self::TYPE_ENTITY => Extension\EntityTypeExtension::class,
        self::TYPE_TEXT => Extension\TextTypeExtension::class,
        self::TYPE_TEXTAREA => Extension\TextareaTypeExtension::class,
        self::TYPE_DATE => Extension\DateTypeExtension::class,
        self::TYPE_SUBMIT => Extension\SubmitTypeExtension::class,
    ];

    /** @var array */
    protected static $excludeAttributs = ['mapping', 'primaryKey', 'foreignKey', 'defaultValue', 'nullable'];

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
        // we convert options to array
        if (null === $options) {
            $options = [];
        }
        $options = json_decode(json_encode($options), true);

        // we cheick extension instance
        if (array_key_exists($type, self::$extensionList)) {
            $instanceValue = self::$extensionList[$type];
        } else {
            $instanceValue = self::$extensionList[self::TYPE_TEXT];
        }

        // we delete all attributes that are not used by extensions
        foreach (self::$excludeAttributs as $attribut) {
            if (isset($options[$attribut])) {
                unset($options[$attribut]);
            }
        }

        // we execute extension
        $this->extensionInstance = new $instanceValue($options);
        $parameters = $this->extensionInstance->getResolverParameters(false, ['templater' => $this]);

        $content = sprintf('->add(\'%s\', %s, [', $child, $this->extensionInstance->getClassExtention()->name) . PHP_EOL;
        foreach ($parameters as $k => $v) {
            if (is_bool($v)) {
                $v = ($v === true) ? 'true' : $v;
                $v = ($v === false) ? 'false' : $v;
                $content .= "            '$k' => $v," . PHP_EOL;
            } elseif (!is_array($v) && strpos($v, 'function') !== false) {
                $content .= "            '$k' => $v," . PHP_EOL;
            } else {
                if (is_array($v)) {
                    $v = var_export($v);
                }
                $content .= "            '$k' => '$v'," . PHP_EOL;
            }
        }

        if ($isEndLine) {
            $content .= '        ]);' .PHP_EOL;
        } else {
            $content .= '        ])' .PHP_EOL;
        }

        return $content;
    }
}

