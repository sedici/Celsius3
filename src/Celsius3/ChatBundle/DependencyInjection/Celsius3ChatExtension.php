<?php
/*
 * Celsius3 - Order management
 * Copyright (C) 2014 PrEBi <info@prebi.unlp.edu.ar>
 *
 * This file is part of Celsius3.
 *
 * Celsius3 is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Celsius3 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Celsius3.  If not, see <http://www.gnu.org/licenses/>.
 */

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
    private $container;

    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $this->container = $container;

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
    }
}