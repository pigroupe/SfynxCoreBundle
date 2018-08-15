<?php
namespace Sfynx\CoreBundle\DependencyInjection\Compiler\Provider\Handler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Definition;

use Sfynx\CoreBundle\DependencyInjection\Compiler\Provider\Generalisation\HandlerPassInterface;
use Sfynx\CoreBundle\DependencyInjection\Compiler\Provider\Handler;

/**
 * Class HandlerFactoryPass
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
class HandlerFactoryPass implements HandlerPassInterface
{
    /** @var string */
    protected $entity;
    /** @var string */
    protected $alias;
    /** @var array */
    protected $parameters;
    /** @var bool */
    protected $multipleEm;

    /**
     * @param string $entity
     * @param string $alias
     * @param array $parameters
     * @param bool $multipleEm
     */
    public function __construct(string $entity, string $alias, array $parameters, bool $multipleEm = true)
    {
        $this->entity = $entity;
        $this->alias = $alias;
        $this->parameters = $parameters;
        $this->multipleEm = $multipleEm;
    }

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $class = $this->parameters['class'];
        $repositoryCommand = $this->parameters['repository_command'];
        $repositoryQuery = $this->parameters['repository_query'];

        $commandQuerybuilder = [];
        $queryQuerybuilder = [];

        if ($this->multipleEm) {
            $providerCommand = $this->parameters['provider_command'];
            $providerQuery = $this->parameters['provider_query'];
            $emCommand = $this->parameters['em_command'];
            $emQuery = $this->parameters['em_query'];

            if ($container->hasParameter("{$this->alias}.repository.{$this->entity}.{$providerCommand}.command.querybuilder")) {
                $commandQuerybuilder = $container->getParameter("{$this->alias}.repository.{$this->entity}.{$providerCommand}.command.querybuilder");
            }
            if ($container->hasParameter("{$this->alias}.repository.{$this->entity}.{$providerQuery}.query.querybuilder")) {
                $queryQuerybuilder = $container->getParameter("{$this->alias}.repository.{$this->entity}.{$providerQuery}.query.querybuilder");
            }

            $container
                ->setDefinition("{$this->alias}.factory.{$this->entity}", new Definition(
                        $container->getParameter("{$this->alias}.factory.{$this->entity}.class"),
                        [
                            $class,
                            $providerCommand,
                            $providerQuery,
                            $repositoryCommand,
                            $repositoryQuery,
                            new Reference($emCommand),
                            new Reference($emQuery),
                            $commandQuerybuilder,
                            $queryQuerybuilder,
                        ]
                    )
                );
        } else {
            $provider = $this->parameters['provider'];
            $em = $this->parameters['em'];

            if ($container->hasParameter("{$this->alias}.repository.{$this->entity}.{$provider}.command.querybuilder")) {
                $commandQuerybuilder = $container->getParameter("{$this->alias}.repository.{$this->entity}.{$provider}.command.querybuilder");
            }
            if ($container->hasParameter("{$this->alias}.repository.{$this->entity}.{$provider}.query.querybuilder")) {
                $queryQuerybuilder = $container->getParameter("{$this->alias}.repository.{$this->entity}.{$provider}.query.querybuilder");
            }

            $container
                ->setDefinition("{$this->alias}.factory.{$this->entity}", new Definition(
                        $container->getParameter("{$this->alias}.factory.{$this->entity}.class"),
                        [
                            $class,
                            $provider,
                            $repositoryCommand,
                            $repositoryQuery,
                            new Reference($em),
                            $commandQuerybuilder,
                            $queryQuerybuilder,
                        ]
                    )
                );
        }

        $container
        ->setAlias("{$this->alias}.manager.{$this->entity}", "{$this->alias}.manager.{$this->entity}.entity");
    }
}
