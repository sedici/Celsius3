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

namespace Celsius3\CoreBundle\Handler;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Security\Http\HttpUtils;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Http\Authentication\DefaultAuthenticationSuccessHandler;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Celsius3\CoreBundle\Manager\UserManager;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class CustomAuthenticationSuccessHandler extends DefaultAuthenticationSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    private $router;
    private $authorization_checker;

    public function __construct(Router $router, AuthorizationChecker $authorization_checker, HttpUtils $httpUtils, array $options)
    {
        parent::__construct($httpUtils, $options);
        $this->router = $router;
        $this->authorization_checker = $authorization_checker;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        $response = parent::onAuthenticationSuccess($request, $token);

        if (!(strpos($response->getTargetUrl(), 'redirect') === false)) {
            return $response;
        }

        if ($this->authorization_checker->isGranted(array(UserManager::ROLE_SUPER_ADMIN))) {
            $response->setTargetUrl($this->router->generate('superadministration'));
        } elseif ($this->authorization_checker->isGranted(array(UserManager::ROLE_ADMIN))) {
            $response->setTargetUrl($this->router->generate('administration'));
        } else {
            $response->setTargetUrl($this->router->generate('user_index'));
        }

        return $response;
    }
}
