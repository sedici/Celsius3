<?php

declare(strict_types=1);

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

namespace Celsius3\Handler;

use Celsius3\Manager\UserManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Http\Authentication\DefaultAuthenticationSuccessHandler;
use Symfony\Component\Security\Http\HttpUtils;


class CustomAuthenticationSuccessHandler extends DefaultAuthenticationSuccessHandler
{
    public function __construct(
        private readonly RouterInterface $router,
        private readonly AuthorizationCheckerInterface $authorization_checker,
        HttpUtils $httpUtils,
        array $options = []
    ) {
        parent::__construct($httpUtils, $options);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): ?Response
    {
        $response = parent::onAuthenticationSuccess($request, $token);

        if (!(!str_contains((string) $response->getTargetUrl(), 'redirect'))) {
            return $response;
        }

        if ($this->authorization_checker->isGranted([UserManager::ROLE_SUPER_ADMIN])) {
            $response->setTargetUrl($this->router->generate('superadministration'));
        } elseif ($this->authorization_checker->isGranted([UserManager::ROLE_ADMIN])) {
            $response->setTargetUrl($this->router->generate('administration'));
        } else {
            $response->setTargetUrl($this->router->generate('user_index'));
        }

        return $response;
    }
}
