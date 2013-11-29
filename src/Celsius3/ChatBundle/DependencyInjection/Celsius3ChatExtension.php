<?php

namespace Celsius3\ChatBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class Celsius3ChatExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        
        if (isset($config['web_socket_server']) && $config['web_socket_server']) {
            $this->setupWebSocketServer($config['web_socket_server']);
        }

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');
    }
    
    private function setupWebSocketServer($config)
    {
        if (isset($config['port']) && $config['port']) {
            $port = (int) $config['port'];
        }

        $this->container->setParameter('celsius3_chat.web_socket_server.port', $port);

        if (isset($config['host']) && $config['host']) {
            $this->container->setParameter('celsius3_chat.web_socket_server.host', $config['host']);
        }

        if (isset($config['zmq_port']) && $config['zmq_port']) {
            $zmq_port = (int) $config['zmq_port'];
        }

        $this->container->setParameter('celsius3_chat.web_socket_server.zmq_port', $zmq_port);

        if (isset($config['zmq_host']) && $config['zmq_host']) {
            $this->container->setParameter('celsius3_chat.web_socket_server.zmq_host', $config['zmq_host']);
        }
    }
}
