<?php
namespace Sfynx\CoreBundle\DependencyInjection\Compiler\Provider\Handler;

use Sfynx\CoreBundle\DependencyInjection\Compiler\Provider\Generalisation\HandlerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Definition;

/**
 * Class HandlerCouchdbFactoryPass
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
class HandlerCouchdbFactoryPass implements HandlerPassInterface
{
    /** @var string */
    protected $entity;
    /** @var string */
    protected $alias;
    /** @var bool */
    protected $multipleEm;

    /**
     * @param string $entity
     * @param string $alias
     */
    public function __construct($entity, $alias, bool $multipleEm = false)
    {
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
                            $container->getParameter("{$this->alias}.repository.{$this->entity}.couchdb.command.class"),
                            $container->getParameter("{$this->alias}.repository.{$this->entity}.couchdb.query.class"),
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
                            $container->getParameter("{$this->alias}.repository.{$this->entity}.couchdb.command.class"),
                            $container->getParameter("{$this->alias}.repository.{$this->entity}.couchdb.query.class"),
                            new Reference($container->getParameter("{$this->alias}.{$this->entity}.entitymanager"))
                        )
                    )
                );
        }

        $container
        ->setAlias("{$this->alias}.manager.{$this->entity}", "{$this->alias}.manager.{$this->entity}.entity");
    }
}
