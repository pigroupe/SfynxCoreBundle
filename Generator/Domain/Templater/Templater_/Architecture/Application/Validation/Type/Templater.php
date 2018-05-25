<?php
namespace Sfynx\CoreBundle\Generator\Domain\Templater\Templater_\Architecture\Application\Validation\Type;

use \stdClass;
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
    public function add(string $child, string $type, stdClass $options = null): string
    {
        if (null === $options) {
            $options = [];
        }

        $instanceValue = self::$extensionList[$type];

        $this->extensionInstance = new $instanceValue(json_decode(json_encode($options), true));

        $parameters = $this->extensionInstance->getResolverParameters(false, ['templater' => $this]);

        // We open the buffer.
        ob_start ();
        ?>->add('<?php echo $child; ?>', <?php echo $this->extensionInstance->getClassExtention()->name; ?>, <?php echo Table::writeArray($parameters) ?>)<?php
        // We retrieve the contents of the buffer.
        $content = ob_get_contents ();
        // We clean the buffer.
        ob_clean ();
        // We close the buffer.
        ob_end_flush ();

        return $content;
    }
}

