<?php

namespace Celsius3\ChatBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('celsius3_chat', 'array')
                ->children()->arrayNode('web_socket_server')->children()
                ->scalarNode('port')->defaultValue('8081')->end()
                ->scalarNode('host')->defaultValue('localhost')->end()
                ->scalarNode('zmq_port')->defaultValue('5556')->end()
                ->scalarNode('zmq_host')->defaultValue('localhost')->end()
                ->end();

        return $treeBuilder;
    }
}
