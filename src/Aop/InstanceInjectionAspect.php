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

namespace Celsius3\Aop;

use JMS\AopBundle\Aop\PointcutInterface;
use CG\Proxy\MethodInterceptorInterface;
use CG\Proxy\MethodInvocation;
use Celsius3\Helper\InstanceHelper;

class InstanceInjectionAspect implements MethodInterceptorInterface, PointcutInterface
{
    private $instance_helper;

    public function __construct(InstanceHelper $instance_helper)
    {
        $this->instance_helper = $instance_helper;
    }

    public function matchesClass(\ReflectionClass $class)
    {
        return false !== strpos($class->name, 'TwigEngine');
    }

    public function matchesMethod(\ReflectionMethod $method)
    {
        return false !== strpos($method->name, 'render');
    }

    public function intercept(MethodInvocation $invocation)
    {
        $invocation->arguments[1]['instance'] = $this->instance_helper->getSessionOrUrlInstance();

        return $invocation->proceed();
    }
}
