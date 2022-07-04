<?php

namespace Celsius3\TicketBundle\Controller;

use Celsius3\Entity\BaseUser;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Celsius3\TicketBundle\Entity\Ticket;
use Celsius3\TicketBundle\Entity\TicketState;

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
        $tickets = $this->get('celsius3_ticket.ticket_manager')->findAll(array(), array('updated_at' => 'ASC'));
        if (!$tickets){
            $tickets=null;
        }
        $repository = $this->getDoctrine()->getManager()->getRepository('Celsius3CoreBundle:BaseUser');
        $administradores = $repository->findAdmins($this->get('celsius3_core.instance_helper')->getSessionInstance());

        return $this->render('Celsius3TicketBundle:Ticket:index.html.twig', array('tickets' => $tickets, 'administradores' => $administradores));
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

        return $this->render('Celsius3TicketBundle:Ticket:show.html.twig', array('ticket' => $ticket));
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

        return $this->render('Celsius3TicketBundle:Ticket:stateCurrent.html.twig', array('state' => $state));
    }

    /**
     * @Route("/update-status", name="ticket_update_status", options={"expose"=true})
     * @Template()
     */
    public function updateStatusAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $id = $request->get('ticket_id');

        $estado_id = $request->get('estado_id');
        $observaciones = $request->get('observaciones');
        $ticket = $em->getRepository('Celsius3TicketBundle:Ticket')->find($id);

        $ticketState = new TicketState();
        $ticketState->setCreatedAt(new \DateTime());
        $ticketState->setUpdatedAt(new \DateTime());
        $ticketState->setDescripcion($observaciones);

        $typeState = $em->getRepository('Celsius3TicketBundle:TypeState')->find($estado_id);

        $ticketState->setTypeState($typeState);
        $ticketState->setTickets($ticket);

        $em = $this->getDoctrine()->getManager();
        $em->persist($ticketState);
        $em->flush();

        $ticket->setStatusCurrent($ticketState);

        $em->flush($ticket);
        $em->flush($ticketState);

        $tickets = $this->get('celsius3_ticket.ticket_manager')->findAll();
        $repository = $this->getDoctrine()->getManager()->getRepository(BaseUser::class);
        $administradores = $repository->findAdmins($this->get('celsius3_core.instance_helper')->getSessionInstance());

        return $this->render('Celsius3TicketBundle:Ticket:index.html.twig', array('tickets' => $tickets, 'administradores' => $administradores));
    }

    /**
     * @Route("/user-assigned", name="ticket_user_assigned", options={"expose"=true})
     * @Template()
     */
    public function userAsignedAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $id = $request->get('ticket_user_id');

        $ticket = $em->getRepository('Celsius3TicketBundle:Ticket')->find($id);

        $admin_id = $request->get('admin_id');
        $userAsigned = $em->getRepository(BaseUser::class)->find($admin_id);

        $em = $this->getDoctrine()->getManager();

        $ticket->setUserAssigned($userAsigned);

        $em->flush($ticket);

        $tickets = $this->get('celsius3_ticket.ticket_manager')->findAll();
        $repository = $this->getDoctrine()->getManager()->getRepository(BaseUser::class);
        $administradores = $repository->findAdmins($this->get('celsius3_core.instance_helper')->getSessionInstance());

        return $this->render('Celsius3TicketBundle:Ticket:index.html.twig', array('tickets' => $tickets, 'administradores' => $administradores));
    }
}
