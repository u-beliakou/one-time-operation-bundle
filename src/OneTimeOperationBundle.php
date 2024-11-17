<?php
declare(strict_types=1);

namespace Ubeliakou\OneTimeOperationBundle;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use Ubeliakou\OneTimeOperationBundle\Executor\ExecutionContextProvider;

class OneTimeOperationBundle extends AbstractBundle
{
    protected string $extensionAlias = 'oto';

    public function configure(DefinitionConfigurator $definition): void
    {
        $rootNode = $definition->rootNode();
        assert($rootNode instanceof ArrayNodeDefinition);

        $rootNode
            ->children()
            ->scalarNode('project')->isRequired()->end()
        ;
    }

    /**
     * @param string[] $config
     */
    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $container->import(__DIR__ . '/../config/services.yaml');

        $container->services()
            ->get(ExecutionContextProvider::class)
            ->arg(0, $config['project']);
    }
}