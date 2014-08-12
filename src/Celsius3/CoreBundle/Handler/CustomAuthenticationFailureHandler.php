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
namespace Celsius3\CoreBundle\Handler;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\DefaultAuthenticationFailureHandler;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Security\Http\HttpUtils;
use Symfony\Component\HttpKernel\Log\LoggerInterface;

class CustomAuthenticationFailureHandler extends
        DefaultAuthenticationFailureHandler implements
        AuthenticationFailureHandlerInterface
{
    private $router;

    public function __construct(Router $router, HttpKernelInterface $httpKernel,
            HttpUtils $httpUtils, array $options,
            LoggerInterface $logger = null)
    {
        parent::__construct($httpKernel, $httpUtils, $options, $logger);
        $this->router = $router;
    }

    public function onAuthenticationFailure(Request $request,
            AuthenticationException $exception)
    {
        $url = $request->attributes->get('url');

        $response = parent::onAuthenticationFailure($request, $exception);
        $response
                ->setTargetUrl(
                        $this->router
                                ->generate('fos_user_security_login',
                                        array('url' => $url)));

        return $response;
    }
}