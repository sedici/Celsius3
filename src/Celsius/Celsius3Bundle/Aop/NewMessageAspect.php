<?php

namespace Celsius\Celsius3Bundle\Aop;

use JMS\AopBundle\Aop\PointcutInterface;
use CG\Proxy\MethodInterceptorInterface;
use CG\Proxy\MethodInvocation;
use Symfony\Component\DependencyInjection\ContainerInterface;
//use Celsius\Celsius3Bundle\Document\Message;

class NewMessageAspect implements MethodInterceptorInterface, PointcutInterface
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    
    public function matchesClass(\ReflectionClass $class)
    {
        return false !== strpos($class->name, 'ThreadManager');
    }

    public function matchesMethod(\ReflectionMethod $method)
    {
        return false !== strpos($method->name, 'saveThread');
    }

    public function intercept(MethodInvocation $invocation)
    {
       $invocation->proceed();
       vardump('Se crea una notifiacion por la creacion de un mensaje');
       //$message = $invocation->arguments[1]['instance'];
       /*Crear notificacion */ 
       
    }
    }