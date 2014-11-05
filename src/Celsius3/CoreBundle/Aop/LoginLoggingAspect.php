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

namespace Celsius3\CoreBundle\Aop;

use JMS\AopBundle\Aop\PointcutInterface;
use CG\Proxy\MethodInterceptorInterface;
use CG\Proxy\MethodInvocation;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Celsius3\CoreBundle\Entity\Login;

class LoginLoggingAspect implements MethodInterceptorInterface, PointcutInterface
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function matchesClass(\ReflectionClass $class)
    {
        return false !== strpos($class->name, 'UsernamePasswordFormAuthenticationListener');
    }

    public function matchesMethod(\ReflectionMethod $method)
    {
        return false !== strpos($method->name, 'attemptAuthentication');
    }

    public function intercept(MethodInvocation $invocation)
    {
        $request = $this->container->get('request_stack')->getCurrentRequest();
        $ip = $request->getClientIp();

        $log = new Login();
        $log->setCategory('login');
        $log->setDate(new \DateTime());

        $em = $this->container->get('doctrine.orm.entity_manager');

        try {
            $token = $invocation->proceed();
            $user = $token->getUsername();
            $log->setMessage(sprintf('%s - User "%s" is now logged in from the IP "%s".', date('Y-m-d H:i:s'), $user, $ip));

            $em->persist($log);
            $em->flush();

            return $token;
        } catch (AuthenticationException $e) {
            $user = $request->request->get('_username');
            $password = $request->request->get('_password');
            $log->setMessage(sprintf('%s - User "%s" tried to login with the password "%s" from the IP "%s".', date('Y-m-d H:i:s'), $user, $password, $ip));
            $em->persist($log);
            $em->flush();
            throw $e;
        }
    }
}