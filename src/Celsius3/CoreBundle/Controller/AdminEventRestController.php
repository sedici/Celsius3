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
        
        $remoteEvents = $this->getDocumentManager()->getRepository('Celsius3CoreBundle:Event\\MultiInstanceEvent')
                ->findBy(array(
            'remoteInstance.id' => $this->getInstance()->getId(),
        ));

        $view = $this->view(array_values(array_merge($events, $remoteEvents)), 200)
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
    
    /**
     * @Post("/{request_id}/{event}", name="admin_rest_order_event", options={"expose"=true})
     */
    public function createEventAction($request_id, $event)
    {
        $dm = $this->getDocumentManager();

        $request = $dm->getRepository('Celsius3CoreBundle:Request')
                ->find($request_id);

        if (!$request) {
            throw $this->createNotFoundException('Unable to find Request.');
        }
        
        $request->setOperator($this->getUser());

        $result = $this->get('celsius3_core.lifecycle_helper')->createEvent($event, $request);

        $view = $this->view($result, 200)
                ->setFormat('json');

        return $this->handleView($view);
    }

}
