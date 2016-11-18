<?php

namespace Celsius3\TicketBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
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
        return $this->render('Celsius3TicketBundle:Ticket:index.html.twig', array('tickets' => $tickets));
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
     * @Route("/show/{id}", name="ticket_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $ticket = $em->getRepository('Celsius3TicketBundle:Ticket')->find($id);
       // dump($ticket);die;
        return $this->render('Celsius3TicketBundle:Ticket:show.html.twig',array('ticket'=>$ticket));
    }


    /**
     * @Route("/stateCurrent", name="state_current", options={"expose"=true})
     * @Template()
     */
    public function stateCurrentAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $id = $request->get('id');

        $state = $em->getRepository('Celsius3TicketBundle:TypeState')->find($id);

        return $this->render('Celsius3TicketBundle:Ticket:stateCurrent.html.twig',array('state'=>$state));
    }
}
