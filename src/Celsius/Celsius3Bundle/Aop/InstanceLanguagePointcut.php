<?php

namespace Celsius\Celsius3Bundle\Aop;

use JMS\AopBundle\Aop\PointcutInterface;

class InstanceLanguagePointcut implements PointcutInterface
{

    public function matchesClass(\ReflectionClass $class)
    {
        return false !== strpos($class->name, 'I18nRouter');
    }

    public function matchesMethod(\ReflectionMethod $method)
    {
        return false !== strpos($method->name, 'match');
    }

}