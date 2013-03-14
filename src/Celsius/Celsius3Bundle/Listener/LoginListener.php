<?php

namespace Celsius\Celsius3Bundle\Listener;

use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class LoginListener
{

    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        $user = $event->getAuthenticationToken()->getUser();

        if ($user instanceof UserInterface)
        {
            $this->session->set('instance_id', $user->getInstance()->getId());
            $this->session->set('instance_url', $user->getInstance()->getUrl());
        }
    }

}
