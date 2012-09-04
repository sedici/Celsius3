<?php

namespace Celsius\Celsius3Bundle\Aop;

use JMS\AopBundle\Aop\PointcutInterface;

class InstanceInjectionPointcut implements PointcutInterface
{

    public function matchesClass(\ReflectionClass $class)
    {
        return false !== strpos($class->name, 'TwigEngine');
    }

    public function matchesMethod(\ReflectionMethod $method)
    {
        return false !== strpos($method->name, 'render');
    }

}