<?php

namespace Celsius3\TicketBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;



/**
 * BaseUser controller.
 *
 * @Route("/")
 */
class TicketController extends Controller
{

    /**
     * @Route("/", name="ticket_index")
     * @Template()
     */

    public function indexAction()
    {
        $tickets = $this->get('celsius3_ticket.ticket_manager')->findAll();

        return $this->render('Celsius3TicketBundle:Ticket:index.html.twig',array('tickets'=>$tickets));
    }


    /**
     * @Route("/new", name="ticket_new")
     * @Template()
     */

    public function newAction()
    {
        return $this->render('Celsius3TicketBundle:Ticket:new.html.twig');
    }
    /**
     * @Route("/show", name="ticket_show")
     * @Template()
     */

    public function showAction()
    {
        return $this->render('Celsius3TicketBundle:Ticket:show.html.twig');
    }
}
