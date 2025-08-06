<?php

namespace Celsius3\TicketBundle\Controller;

use Celsius3\Controller\Core\Controller;
use Celsius3\Controller\Core\EntityController;
use Celsius3\Entity\BaseUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Celsius3\TicketBundle\Entity\Ticket;
use Celsius3\TicketBundle\Entity\TicketState;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;

#[
    Route('/'),
    IsGranted('ROLE_TICKET')
]
class TicketController extends EntityController
{

    public function initialize(): void
    {
        $this->setEntity(Ticket::class);

        parent::initialize();

        $this->setInstanceDependent(true);
        $this->setSortDefaults(['updated_at' => 'ASC']);
        $this->htmlRenderer->setTemplatePrefix('Celsius3TicketBundle:Ticket:');
    }


    #[Route('/', name: 'ticket_index')]
    // #[Template]
    public function indexAction()
    {
        $tickets = $this->repository->findAll();
        $repository = $this->entityManager->getRepository(BaseUser::class);
        $administradores = $repository->findAdmins($this->instance);

        return $this->htmlRenderer->render(
            'index',
            [
                'tickets' => $tickets,
                'administradores' => $administradores
            ]
        );
    }


    #[Route(path: '/new', name: 'ticket_new')]
    #[Template]
    public function newAction(): Response
    {
        return $this->htmlRenderer->render(
            'Celsius3TicketBundle:Ticket:new.html.twig'
        );
    }


    #[Route(path: '/show/{id}', name: 'ticket_show')]
    #[Template]
    public function showAction($id)
    {
        return $this->show($id);
        // $ticket = $this->findQuery($id);

        // return $this->render('Celsius3TicketBundle:Ticket:show.html.twig', ['ticket' => $ticket]);
    }


    #[Route(path: '/stateCurrent', name: 'state_current', options: ['expose' => true])]
    #[Template]
    public function stateCurrentAction(Request $request)
    {
        $em = $this->entityManager;
        $id = $request->get('id');

        $state = $em->getRepository('Celsius3TicketBundle:TypeState')->find($id);

        return $this->htmlRenderer->render(
            'Celsius3TicketBundle:Ticket:stateCurrent.html.twig',
            ['state' => $state]
        );
    }


    #[Route(path: '/update-status', name: 'ticket_update_status', options: ['expose' => true])]
    #[Template]
    public function updateStatusAction(Request $request)
    {
        $em = $this->entityManager;
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

        $em = $this->entityManager;
        $em->persist($ticketState);
        $em->flush();

        $ticket->setStatusCurrent($ticketState);

        $em->flush();
        $em->flush();

        $tickets = $this->repository->findAll();
        $repository = $this->entityManager->getRepository(BaseUser::class);
        $administradores = $repository->findAdmins($this->instance);

        return $this->htmlRenderer->render(
            'Celsius3TicketBundle:Ticket:index.html.twig',
            [
                'tickets' => $tickets,
                'administradores' => $administradores
            ]
        );
    }

    #[Route(path: '/user-assigned', name: 'ticket_user_assigned', options: ['expose' => true])]
    // #[Template]
    public function userAsignedAction(Request $request)
    {
        $em = $this->entityManager;
        $id = $request->get('ticket_user_id');

        $ticket = $em->getRepository('Celsius3TicketBundle:Ticket')->find($id);

        $admin_id = $request->get('admin_id');
        $userAsigned = $em->getRepository(BaseUser::class)->find($admin_id);

        $em = $this->entityManager;

        $ticket->setUserAssigned($userAsigned);

        $em->flush();

        $tickets = $this->repository->findAll();
        $repository = $this->entityManager->getRepository(BaseUser::class);
        $administradores = $repository->findAdmins($this->instance);

        return $this->htmlRenderer->render(
            'Celsius3TicketBundle:Ticket:index.html.twig',
            [
                'tickets' => $tickets,
                'administradores' => $administradores
            ]
        );
    }
}
