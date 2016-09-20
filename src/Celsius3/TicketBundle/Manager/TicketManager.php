<?php

namespace Celsius3\TicketBundle\Manager;


use Symfony\Component\DependencyInjection\ContainerInterface;

class TicketManager
{

    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }


    public function findAll(){

        $em = $this->container->get('doctrine.orm.entity_manager');
        $tickets = $em->getRepository('Celsius3TicketBundle:Ticket')
            ->findAll();
        return $tickets;
    }


}
