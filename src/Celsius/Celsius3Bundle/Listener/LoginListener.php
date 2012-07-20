<?php

namespace Celsius\Celsius3Bundle\Listener;

use Symfony\Component\DependencyInjection\ContainerInterface;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class LoginListener
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        $user = $event->getAuthenticationToken()->getUser();

        if ($user instanceof UserInterface) {
            $this->container->get('session')->set('instance_id',$user->getInstance()->getId());
            $this->container->get('session')->set('instance_url',$user->getInstance()->getUrl());
        }
    }
}
