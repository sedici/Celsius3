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

namespace Celsius3\CoreBundle\Helper;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Celsius3\CoreBundle\Exception\InstanceNotFoundException;

class InstanceHelper
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getSessionInstance()
    {
        $instance = $this->container
                ->get('doctrine.orm.entity_manager')
                ->getRepository('Celsius3CoreBundle:Instance')
                ->find($this->container->get('session')->get('instance_id'));

        if (!$instance) {
            throw new InstanceNotFoundException('Unable to find Instance.');
        }

        return $instance;
    }

    public function getUrlInstance()
    {
        $request = $this->container->get('request_stack')->getCurrentRequest();

        $instance = $this->container
                ->get('doctrine.orm.entity_manager')
                ->getRepository('Celsius3CoreBundle:Instance')
                ->findOneBy(array(
            'host' => $request->getHost(),
        ));

        if (!$instance) {
            throw new InstanceNotFoundException('Unable to find Instance.');
        }

        return $instance;
    }

    public function getSessionOrUrlInstance()
    {
        $request = $this->container->get('request_stack')->getCurrentRequest();

        if ($this->container->get('session')->has('instance_url')) {
            $instance = $this->container->get('doctrine.orm.entity_manager')
                    ->getRepository('Celsius3CoreBundle:Instance')
                    ->findOneBy(array('url' => $this->container->get('session')->get('instance_url')));
        } else {
            $instance = $this->container
                    ->get('doctrine.orm.entity_manager')
                    ->getRepository('Celsius3CoreBundle:Instance')
                    ->findOneBy(array(
                'host' => $request->getHost(),
            ));
        }

        return $instance;
    }
}
