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

namespace Celsius3\CoreBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\DependencyInjection\Container;

class MaintenanceModeListener
{

    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $filename = $this->container->getParameter('maintenance_mode_file');
        $modedir = $this->container->getParameter('maintenance_mode_dir');
        $file = __DIR__ . '/../../../..' . $modedir . $filename;
                
        if (file_exists($file)) {
            $template = $this->container->getParameter('maintenance_mode_template');
            $response = $this->container->get('templating')->renderResponse($template);
            $event->setResponse($response);
            
            return;
        }

        return;
    }

}
