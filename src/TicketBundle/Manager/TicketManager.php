<?php

namespace Celsius3\TicketBundle\Manager;

use Doctrine\ORM\EntityManager;

class TicketManager
{
    public function __construct(private readonly EntityManager $entityManager)
    {
    }

    public function findAll()
    {
        return $this->entityManager
            ->getRepository('Celsius3TicketBundle:Ticket')
            ->findAll();
    }
}
