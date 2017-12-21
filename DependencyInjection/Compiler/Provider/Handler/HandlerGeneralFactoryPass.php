<?php
namespace Sfynx\CoreBundle\DependencyInjection\Compiler\Provider\Handler;

use Sfynx\CoreBundle\DependencyInjection\Compiler\Provider\Generalisation\HandlerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class HandlerOrmFactoryPass
 *
 * @category   Bundle
 * @package    Sfynx\CoreBundle
 * @subpackage DependencyInjection\Compiler\Provider\Handler
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
 */
class HandlerGeneralFactoryPass implements HandlerPassInterface
{
    /** @var string */
    protected $type = 'orm';
    /** @var string */
    protected $entity;
    /** @var string */
    protected $alias;
    /** @var bool */
    protected $multipleEm;

    /**
     * @param string $type
     * @param string $entity
     * @param string $alias
     */
    public function __construct($type, $entity, $alias, bool $multipleEm = false)
    {
        $this->type = $type;
        $this->entity = $entity;
        $this->alias = $alias;
        $this->multipleEm = $multipleEm;
    }

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if ($this->multipleEm) {
            $container
                ->setDefinition("{$this->alias}.factory.{$this->entity}", new Definition(
                        $container->getParameter("{$this->alias}.factory.{$this->entity}.class"),
                        array(
                            $container->getParameter("{$this->alias}.manager.{$this->entity}.params")['class'],
                            $container->getParameter("{$this->alias}.repository.{$this->entity}.{$this->type}.command.class"),
                            $container->getParameter("{$this->alias}.repository.{$this->entity}.{$this->type}.query.class"),
                            new Reference($container->getParameter("{$this->alias}.{$this->entity}.entitymanager.command")),
                            new Reference($container->getParameter("{$this->alias}.{$this->entity}.entitymanager.query"))
                        )
                    )
                );
        } else {
            $container
                ->setDefinition("{$this->alias}.factory.{$this->entity}", new Definition(
                        $container->getParameter("{$this->alias}.factory.{$this->entity}.class"),
                        array(
                            $container->getParameter("{$this->alias}.manager.{$this->entity}.params")['class'],
                            $container->getParameter("{$this->alias}.repository.{$this->entity}.{$this->type}.command.class"),
                            $container->getParameter("{$this->alias}.repository.{$this->entity}.{$this->type}.query.class"),
                            new Reference($container->getParameter("{$this->alias}.{$this->entity}.entitymanager"))
                        )
                    )
                );
        }

        $container
            ->setAlias("{$this->alias}.manager.{$this->entity}", "{$this->alias}.manager.{$this->entity}.{$this->type}");
    }
}
