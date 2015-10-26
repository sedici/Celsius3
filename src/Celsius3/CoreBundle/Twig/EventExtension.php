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

namespace Celsius3\CoreBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Celsius3\CoreBundle\Entity\Event\MultiInstanceRequestEvent;
use Celsius3\CoreBundle\Entity\Event\SearchEvent;
use Celsius3\CoreBundle\Entity\Request;
use Celsius3\CoreBundle\Manager\CatalogManager;

class EventExtension extends \Twig_Extension
{
    private $environment;
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('get_request_state', array($this, 'getRequestState')),
            new \Twig_SimpleFunction('count_searches', array($this, 'countSearches')),
        );
    }

    public function getRequestState(MultiInstanceRequestEvent $request)
    {
        return $request->getOrder()->getCurrentState($request->getRemoteInstance());
    }

    public function countSearches(Request $request)
    {
        return $request->getEvents()->filter(function($e) {
            return $e instanceof SearchEvent && $e->getResult() !== CatalogManager::CATALOG__NON_SEARCHED;
        })->count();
    }

    public function getName()
    {
        return 'celsius3_core.event_extension';
    }
}
