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
use Symfony\Component\HttpFoundation\Request;
use Celsius3\CoreBundle\Entity\BaseUser;

class InstanceConfigurationListener
{

    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $instance = $this->container->get('celsius3_core.instance_helper')->getSessionOrUrlInstance();
        $token = $this->container->get('security.context')->getToken();

        if (!is_null($instance) && !is_null($token) && (($user = $token->getUser()) instanceof BaseUser)) {
            if (($user->hasRole('ROLE_ADMIN') || $user->hasRole('ROLE_SUPER_ADMIN'))) {
                $mailerHelper = $this->container->get('celsius3_core.mailer_helper');
                $bag = $this->container->get('session')->getFlashBag();

                if (!$mailerHelper->validateSmtpServerData($instance) && !$bag->has('warning')) {
                    $message = $this->container->get('translator')->trans('smtp-message');
                    $bag->set('configuration', array($message));
                } elseif ($mailerHelper->validateSmtpServerData($instance)) {
                    $bag->set('configuration', array());
                }

                return;
            }
        }
    }

}
