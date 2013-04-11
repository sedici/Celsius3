<?php

namespace Celsius\Celsius3Bundle\Aop;

use JMS\AopBundle\Aop\PointcutInterface;
use CG\Proxy\MethodInterceptorInterface;
use CG\Proxy\MethodInvocation;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Celsius\Celsius3Bundle\Document\Notification;
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
       $notification = new Notification;
       /*La fecha de creacion se setea sola*/
       $notification->setCause('NewMessage');
       $notification->setSource($this->container->get('instance_helper')->getSessionInstance());
       $receiver = $invocation->arguments[0]->getParticipants();
       $notification->addUsers($receiver[0]);
       
  //     echo '<pre>';
  //     var_dump($invocation->arguments[0]);die;
  //     echo '</pre>';
       $this->container->get('doctrine.odm.mongodb.document_manager')->persist($notification);
       $this->container->get('doctrine.odm.mongodb.document_manager')->flush();
    }
}