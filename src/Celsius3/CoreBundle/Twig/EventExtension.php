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
use Celsius3\CoreBundle\Document\Event\Event;
use Celsius3\CoreBundle\Document\Event\MultiInstanceRequest;
use Celsius3\CoreBundle\Document\Order;

class EventExtension extends \Twig_Extension
{

    private $environment;
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }

    public function getFunctions()
    {
        return array(
            'render_request_event' => new \Twig_Function_Method($this, 'renderRequestEvent'),
            'render_receive_event' => new \Twig_Function_Method($this, 'renderReceiveEvent'),
            'get_request_state' => new \Twig_Function_Method($this, 'getRequestState'),);
    }

    public function renderRequestEvent(Event $event, Order $order)
    {
        return $this->environment->render('Celsius3CoreBundle:AdminOrder:_event_request.html.twig', $this->container->get('celsius3_core.event_manager')->getDataForRequestRendering($event, $order));
    }

    public function renderReceiveEvent(Event $event, Order $order)
    {
        return $this->environment->render('Celsius3CoreBundle:AdminOrder:_event_receive.html.twig', $this->container->get('celsius3_core.event_manager')->getDataForReceiveRendering($event, $order));
    }

    public function getRequestState(MultiInstanceRequest $request)
    {
        return $request->getOrder()->getCurrentState($request->getRemoteInstance());
    }

    public function getName()
    {
        return 'celsius3_core.event_extension';
    }

}