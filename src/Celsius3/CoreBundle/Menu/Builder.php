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

namespace Celsius3\CoreBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;
use Celsius3\CoreBundle\Manager\UserManager;

class Builder extends ContainerAware
{

    public function topMenu(FactoryInterface $factory, array $options)
    {
        $request = $this->container->get('request_stack')->getCurrentRequest();
        $securityContext = $this->container->get('security.context');

        $instance_url = $request->attributes->has('url') ? $request->attributes->get('url') : $this->container->get('session')->get('instance_url');

        $local = $request->attributes->has('url') &&
                $request->attributes->get('url') == $this->container->get('session')->get('instance_url') ||
                !$request->attributes->has('url');

        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav navbar-nav main-navbar');

        $menu->addChild('Home', array(
            'route' => 'public_index',
            'routeParameters' => array(
                'url' => $instance_url,
            ),
        ));
        if ($securityContext->isGranted(UserManager::ROLE_ADMIN) !== false && $local) {
            $menu->addChild('Administration', array(
                'route' => 'administration',
            ));
            $user = $securityContext->getToken()->getUser();
            if ($user->getAdministeredInstances()->count() > 0) {
                if (!$user->getAdministeredInstances()->contains($user->getInstance())) {
                    $user->getAdministeredInstances()->add($user->getInstance());
                }

                $menu->addChild('Others', array(
                            'uri' => '#',
                            'linkAttributes' => array(
                                'class' => 'dropdown-toggle',
                                'data-toggle' => 'dropdown',
                            ),
                            'label' => '<b class="caret"></b>',
                            'extras' => array(
                                'safe_label' => true,
                            ),
                        ))
                        ->setChildrenAttributes(array(
                            'class' => 'dropdown-menu',
                ));

                foreach ($user->getAdministeredInstances() as $instance) {
                    $class = $instance->getUrl() == $instance_url ? 'active' : '';
                    $menu['Others']->addChild($instance->getName(), array(
                        'route' => 'administration_change_context',
                        'routeParameters' => array(
                            'id' => $instance->getId(),
                        ),
                        'attributes' => array(
                            'class' => $class,
                        ),
                    ));
                }
            }
        }
        if ($securityContext->isGranted(UserManager::ROLE_SUPER_ADMIN) !== false && $local) {
            $menu->addChild('Network Administration', array(
                'route' => 'superadministration',
            ));
        }
        if ($securityContext->isGranted(UserManager::ROLE_USER) !== false && $local) {
            $menu->addChild('My Site', array(
                'route' => 'user_index',
            ));
        }
        if ($securityContext->isGranted(UserManager::ROLE_USER) !== false && !$local) {
            $menu->addChild('My Instance', array(
                'route' => 'public_index',
                'routeParameters' => array(
                    'url' => $this->container->get('session')->get('instance_url'),
                ),
            ));
        }

        return $menu;
    }

    public function directoryMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav nav-pills');

        $menu->addChild('Home', array(
            'route' => 'directory',
        ));
        $menu->addChild('Instances', array(
            'route' => 'directory_instances',
        ));

        return $menu;
    }

    public function publicMenu(FactoryInterface $factory, array $options)
    {
        $request = $this->container->get('request_stack')->getCurrentRequest();

        $instance_url = $request->attributes->has('url') ? $request->attributes->get('url') : $this->container->get('session')->get('instance_url');

        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav nav-pills');

        $menu->addChild('Home', array(
            'route' => 'public_index',
            'routeParameters' => array(
                'url' => $instance_url,
            ),
        ));
        $menu->addChild('News', array(
            'route' => 'public_news',
            'routeParameters' => array(
                'url' => $instance_url,
            ),
        ));
        $menu->addChild('Information', array(
            'route' => 'public_information',
            'routeParameters' => array(
                'url' => $instance_url,
            ),
        ));
        $menu->addChild('Statistics', array(
            'route' => 'public_statistics',
            'routeParameters' => array(
                'url' => $instance_url,
            ),
        ));

        return $menu;
    }
}
