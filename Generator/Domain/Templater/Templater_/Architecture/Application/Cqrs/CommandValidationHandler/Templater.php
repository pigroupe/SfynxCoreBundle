<?php
namespace Sfynx\CoreBundle\Generator\Domain\Templater\Templater_\Architecture\Application\Cqrs\CommandValidationHandler;

use Sfynx\CoreBundle\Generator\Domain\Widget\Generalisation\Interfaces\WidgetInterface;
use Sfynx\CoreBundle\Generator\Domain\Templater\Generalisation\Interfaces\TemplaterInterface;
use Sfynx\CoreBundle\Generator\Domain\Templater\Generalisation\AbstractTemplater;

/**
 * @category   Sfynx\CoreBundle\Generator
 * @package    Domain
 * @subpackage TemplaterTemplater_\Architecture\Application\Cqrs\CommandValidationHandler
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class Templater extends AbstractTemplater implements TemplaterInterface
{
    /** @var string */
    const TAG = 'templater_archi_app_cqrs_commandvalidationhandler';

    /**
     * @inheritdoc
     */
    public static function scriptList(string $template): array
    {
        return [
            '0' => [
                'Application\Cqrs',
                __DIR__ . "/Resources/${template}/CommandValidationHandler.php",
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        return 'Validation Handler class';
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
This class expose validationHandler component
EOT;
    }
}

