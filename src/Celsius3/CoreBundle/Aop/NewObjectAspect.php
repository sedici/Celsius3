<?php

namespace Celsius3\CoreBundle\Aop;

use JMS\AopBundle\Aop\PointcutInterface;
use CG\Proxy\MethodInterceptorInterface;
use CG\Proxy\MethodInvocation;
use Symfony\Component\DependencyInjection\ContainerInterface;


class NewObjectAspect implements MethodInterceptorInterface, PointcutInterface
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function matchesClass(\ReflectionClass $class)
    {
        return false !== strpos($class->name, 'Celsius3\CoreBundle\Controller\BaseController');
    }

    public function matchesMethod(\ReflectionMethod $method)
    {
        return false !== strpos($method->name, 'persistDocument');
    }

    public function intercept(MethodInvocation $invocation)
    {
        echo 'holaaa';
        var_dump($invocation->arguments);die;
    }
}