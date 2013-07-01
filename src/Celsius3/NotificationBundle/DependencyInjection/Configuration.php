<?php

namespace Celsius3\NotificationBundle\DependencyInjection;
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
        $rootNode = $treeBuilder->root('celsius3_notification', 'array')
                ->children()->arrayNode('web_socket_server')->children()
                ->scalarNode('port')->defaultValue('8080')->end()
                ->scalarNode('host')->defaultValue('localhost')->end()->end();

        return $treeBuilder;
    }
}
