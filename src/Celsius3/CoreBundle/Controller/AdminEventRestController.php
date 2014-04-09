<?php

namespace Celsius3\CoreBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;

/**
 * User controller.
 *
 * @Route("/admin/rest/event")
 */
class AdminEventRestController extends BaseInstanceDependentRestController
{

    /**
     * GET Route annotation.
     * @Get("/{request_id}", name="admin_rest_events", options={"expose"=true})
     */
    public function getAllEventsAction($request_id)
    {
        $events = $this->getDocumentManager()->getRepository('Celsius3CoreBundle:Event\\Event')
                ->findBy(array(
            'request.id' => $request_id,
        ));

        $view = $this->view(array_values($events), 200)
                ->setFormat('json');

        return $this->handleView($view);
    }

    /**
     * GET Route annotation.
     * @Get("/{request_id}/{event}", name="admin_rest_event", options={"expose"=true})
     */
    public function getEventsAction($request_id, $event)
    {
        $events = $this->get('celsius3_core.event_manager')->getEvents($event, $request_id);

        $view = $this->view(array_values($events), 200)
                ->setFormat('json');

        return $this->handleView($view);
    }

    /**
     * GET Route annotation.
     * @Get("/{id}", name="admin_rest_event_get", options={"expose"=true})
     */
    public function getEventAction($id)
    {
        $dm = $this->getDocumentManager();

        $event = $dm->getRepository('Celsius3CoreBundle:Event')
                ->find($id);

        if (!$event) {
            return $this->createNotFoundException('Event not found.');
        }

        $view = $this->view($event, 200)
                ->setFormat('json');

        return $this->handleView($view);
    }

}
