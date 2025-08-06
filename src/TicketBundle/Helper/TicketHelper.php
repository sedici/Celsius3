<?php

namespace Celsius3\TicketBundle\Helper;

use Celsius3\TicketBundle\Entity\Ticket;
use Celsius3\TicketBundle\Entity\TicketState;
use Doctrine\ORM\EntityManagerInterface;

class TicketHelper
{
    private $parametros;

    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function setParametros($parametros)
    {
        $this->parametros = $parametros;
    }

    public function getParametros()
    {
        return $this->parametros;
    }

    public function createTicket()
    {
        $em = $this->entityManager;

        $param = $this->getParametros();

        $ticket = new Ticket();
        $ticket->setSubject($param['subject']);
        $ticket->setText($param['texto']);

        $priority = $em->getRepository('Celsius3TicketBundle:Priority')->find($param['priority']);
        $ticket->setPriority($priority);

        $category = $em->getRepository('Celsius3TicketBundle:Category')->find($param['category']);

        $ticket->setCategory($category);
        $em->persist($ticket);

        $em->flush();

        $ticketState = new TicketState();
        $ticketState->setCreatedAt(new \DateTime());
        $ticketState->setUpdatedAt(new \DateTime());

        $typeState = $em->getRepository('Celsius3TicketBundle:TypeState')->find($param['typeState']);

        $ticketState->setTypeState($typeState);
        $ticketState->setTickets($ticket);
        $em->persist($ticketState);

        $ticket->setStatusCurrent($ticketState);

        $em->flush();

        $em->flush();
    }
}
