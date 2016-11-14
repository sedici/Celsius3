<?php

/*
 * Celsius3 - Order management
 * Copyright (C) 2014 PREBI-SEDICI <info@prebi.unlp.edu.ar> http://prebi.unlp.edu.ar http://sedici.unlp.edu.ar
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
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class Builder
{
    use ContainerAwareTrait;

    public function directoryMenu(FactoryInterface $factory)
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

    public function publicMenu(FactoryInterface $factory)
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
        $menu->addChild('Help', array(
            'route' => 'public_help',
        ))->setExtra('translation_domain', 'messages');

        return $menu;
    }
}
