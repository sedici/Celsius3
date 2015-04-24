<?php

namespace Celsius3\ApiBundle\EventListener;

use FOS\OAuthServerBundle\Event\OAuthEvent;
use Doctrine\ORM\EntityManager;
use FOS\UserBundle\Doctrine\UserManager;

class OAuthEventListener
{
    private $em;
    private $um;
    
    function __construct(EntityManager $em, UserManager $um)
    {
        $this->em = $em;
        $this->um = $um;
    }
    
    public function onPreAuthorizationProcess(OAuthEvent $event)
    {
        if ($user = $this->getUser($event)) {
            $event->setAuthorizedClient(
                $user->isAuthorizedClient($event->getClient())
            );
        }
    }

    public function onPostAuthorizationProcess(OAuthEvent $event)
    {
        if ($event->isAuthorizedClient()) {
            if (null !== $client = $event->getClient()) {
                $user = $this->getUser($event);
                $user->addClientApplication($client);
                $this->em->persist($user);
                $this->em->flush();
            }
        }
    }

    protected function getUser(OAuthEvent $event)
    {
        
        return $this->um->findUserByUsername($event->getUser()->getUsername());
//                getRepository('Celsius3CoreBundle:BaseUser')
//                ->findOneBy(array('username' => $event->getUser()->getUsername()));
    }
}