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
        return $this->entityManager
            ->getRepository('Celsius3TicketBundle:Ticket')
            ->findAll();
    }
}
