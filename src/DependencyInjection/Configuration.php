<?php

namespace Myowncode\TurboSmsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 *
 * @package \Myowncode\TurboSmsBundle\DependencyInjection
 */
class Configuration implements ConfigurationInterface
{

    /**
     * Generates the configuration tree builder.
     *
     * @return \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition|\Symfony\Component\Config\Definition\Builder\NodeBuilder|\Symfony\Component\Config\Definition\Builder\NodeDefinition|\Symfony\Component\Config\Definition\Builder\NodeParentInterface|\Symfony\Component\Config\Definition\Builder\ParentNodeDefinitionInterface|\Symfony\Component\Config\Definition\Builder\VariableNodeDefinition
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        $rootNode =  $treeBuilder->root('myowncode_turbosms');
        $rootNode
            ->children()
                ->scalarNode('login')
                    ->isRequired()
                    ->cannotBeEmpty()
                    ->end()
                ->scalarNode('password')
                    ->isRequired()
                    ->cannotBeEmpty()
                    ->end()
                ->scalarNode('sender')
                    ->isRequired()
                    ->cannotBeEmpty()
                    ->end()
                ->scalarNode('debug')
                    ->defaultFalse()
                    ->end()
                ->scalarNode('save_to_db')
                    ->defaultTrue()
                    ->end()
                ->scalarNode('wsdl')
                    ->isRequired()
                    ->cannotBeEmpty()
                    ->end()
            ->end();

        return $treeBuilder;
    }
}