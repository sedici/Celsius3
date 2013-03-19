<?php

namespace Celsius\Celsius3Bundle\Aop;

use JMS\AopBundle\Aop\PointcutInterface;
use CG\Proxy\MethodInterceptorInterface;
use CG\Proxy\MethodInvocation;
use Celsius\Celsius3Bundle\Helper\InstanceHelper;

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
        $invocation->arguments[1]['instance'] = $this->instance_helper->getUrlOrSessionInstance();
        
        return $invocation->proceed();
    }

}