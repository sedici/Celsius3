<?php

namespace Celsius3\CoreBundle\EventListener;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\EventListener\AuthenticationListener as FOSAuthenticationListener;

class AuthenticationListener extends FOSAuthenticationListener
{

    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::REGISTRATION_COMPLETED => 'authenticate',
            FOSUserEvents::REGISTRATION_CONFIRMED => 'authenticate'
        );
    }

}
