<?php

namespace Celsius\Celsius3Bundle\Aop;

use JMS\AopBundle\Aop\PointcutInterface;

class LoginLoggingPointcut implements PointcutInterface
{

    public function matchesClass(\ReflectionClass $class)
    {
        return false !== strpos($class->name, 'UsernamePasswordFormAuthenticationListener');
    }

    public function matchesMethod(\ReflectionMethod $method)
    {
        return false !== strpos($method->name, 'attemptAuthentication');
    }

}
