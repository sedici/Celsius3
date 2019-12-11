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

namespace Celsius3\CoreBundle\EventListener;

use Celsius3\CoreBundle\Helper\InstanceHelper;
use Celsius3\CoreBundle\Manager\InstanceManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Core\Security;
use Twig\Environment;

class InstanceDisabledListener
{
    private $instanceHelper;
    private $twig;
    private $security;
    private $router;

    public function __construct(InstanceHelper $instanceHelper, Environment $twig, Security $security, Router $router)
    {
        $this->instanceHelper = $instanceHelper;
        $this->twig = $twig;
        $this->security = $security;
        $this->router = $router;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $instance = $this->instanceHelper->getSessionOrUrlInstance();

        if ($instance && !$instance->getEnabled() && $instance->getUrl() !== InstanceManager::INSTANCE__DIRECTORY) {
            if ($this->security->isGranted('IS_AUTHENTICATED_FULLY')) {
                $event->setResponse(new RedirectResponse($this->router->generate('fos_user_security_logout')));
                return;
            }

            $view = $this->twig->render('Celsius3CoreBundle:Public:instance_disabled.html.twig');
            $response = new Response($view);
            $event->setResponse($response);
        }
    }
}