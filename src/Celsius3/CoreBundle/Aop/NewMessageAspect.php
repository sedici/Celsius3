<?php

namespace Celsius3\CoreBundle\Aop;

use JMS\AopBundle\Aop\PointcutInterface;
use CG\Proxy\MethodInterceptorInterface;
use CG\Proxy\MethodInvocation;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Celsius3\CoreBundle\Manager\NotificationManager;
use Celsius3\CoreBundle\Document\Notification;


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
//        $notification = new Notification;
//        /*La fecha de creacion se setea sola*/
//        $notification->setCause(NotificationManager::CAUSE__NEW__MESSAGE);
//        $notification->setSource($this->container->get('celsius3_core.instance_helper')->getSessionInstance());
//        //$notification->setSource($invocation->arguments[0]->getCreatedBy()->getInstance());
//        $receiver = $invocation->arguments[0]->getParticipants();
//        $notification->setUser($receiver[0]);
//    //    echo '<pre>';
//    //    var_dump($invocation->arguments[0]->getCreatedBy()->getInstance()->getId());
//    //    echo '</pre>';
//    //    die;
//        $this->container->get('doctrine.odm.mongodb.document_manager')->persist($notification);
//        $this->container->get('doctrine.odm.mongodb.document_manager')->flush();
    }
}