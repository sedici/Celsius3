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

    public function directoryMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav nav-pills');

        $menu->addChild('Home', array(
            'route' => 'directory_homepage',
        ))->setExtra('translation_domain', 'messages');
        $menu->addChild('Instances', array(
            'route' => 'directory_instances',
        ))->setExtra('translation_domain', 'messages');
        $menu->addChild('Statistics', array(
            'route' => 'directory_statistics',
        ))->setExtra('translation_domain', 'messages');

        return $menu;
    }

    public function publicMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav nav-pills');

        $menu->addChild('Home', array(
            'route' => 'public_index',
        ))->setExtra('translation_domain', 'messages');
        $menu->addChild('News', array(
            'route' => 'public_news',
        ))->setExtra('translation_domain', 'messages');
        $menu->addChild('Information', array(
            'route' => 'public_information',
        ))->setExtra('translation_domain', 'messages');
        $menu->addChild('Statistics', array(
            'route' => 'public_statistics',
        ))->setExtra('translation_domain', 'messages');

        return $menu;
    }
}
