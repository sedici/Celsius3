<?php

namespace Celsius3\TicketBundle\Manager;

use Doctrine\ORM\EntityManager;

class TicketManager
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function findAll()
    {
        $em = $this->entityManager;
        $tickets = $em->getRepository('Celsius3TicketBundle:Ticket')
            ->findAll();

        return $tickets;
    }
}
