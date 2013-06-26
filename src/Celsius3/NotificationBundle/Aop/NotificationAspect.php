<?php

namespace Celsius3\NotificationBundle\Aop;
use JMS\AopBundle\Aop\PointcutInterface;
use CG\Proxy\MethodInterceptorInterface;
use CG\Proxy\MethodInvocation;
use Celsius3\NotificationBundle\Document\Notifiable;
use Celsius3\NotificationBundle\Manager\NotificationManager;

class NotificationAspect implements MethodInterceptorInterface, PointcutInterface
{
    private $manager;

    public function __construct(NotificationManager $manager)
    {
        $this->manager = $manager;
    }

    public function matchesClass(\ReflectionClass $class)
    {
        return false !== strpos($class->name, 'DocumentManager');
    }

    public function matchesMethod(\ReflectionMethod $method)
    {
        return false !== strpos($method->name, 'persist');
    }

    public function intercept(MethodInvocation $invocation)
    {
        $result = $invocation->proceed();
        if ($invocation->arguments[0] instanceof Notifiable) {
            die();
            $invocation->arguments[0]->notify($manager);
        }

        return $result;
    }
}
