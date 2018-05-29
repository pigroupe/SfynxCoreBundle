<?php
namespace Sfynx\CoreBundle\Generator\Domain\Templater\Templater_\Architecture\Application\Cqrs\Command;

use Sfynx\CoreBundle\Generator\Domain\Widget\Generalisation\Interfaces\WidgetInterface;
use Sfynx\CoreBundle\Generator\Domain\Templater\Generalisation\Interfaces\TemplaterInterface;
use Sfynx\CoreBundle\Generator\Domain\Templater\Generalisation\AbstractTemplater;

/**
 * @category   Sfynx\CoreBundle\Generator
 * @package    Domain
 * @subpackage TemplaterTemplater_\Architecture\Application\Cqrs\Command
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class Templater extends AbstractTemplater implements TemplaterInterface
{
    /** @var string */
    const TAG = 'templater_archi_app_cqrs_command';

    /**
     * @inheritdoc
     */
    public static function scriptList(string $template): array
    {
        return [
            '0' => [
                'Application\Cqrs',
                __DIR__ . "/Resources/${template}/Command.php",
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        return 'Command class';
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
This class expose command component
EOT;
    }
}

