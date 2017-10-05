<?php

/*
 * Celsius3 - Order management
 * Copyright (C) 2014 PREBI-SEDICI <info@prebi.unlp.edu.ar> http://prebi.unlp.edu.ar http://sedici.unlp.edu.ar
 *
 * This file is part of Celsius3.
 *
 * Celsius3 is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Celsius3 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Celsius3.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Celsius3\CoreBundle\Controller;

use Celsius3\CoreBundle\Entity\Event\Event;
use Celsius3\CoreBundle\Exception\Exception;
use Doctrine\ORM\EntityManager;
use JMS\Serializer\SerializationContext;
use Symfony\Component\HttpFoundation\Request;

/**
 * User controller.
 *
 * @Route("/admin/rest/event")
 */
class AdminEventRestController extends BaseInstanceDependentRestController
{
    /**
     * GET Route annotation.
     *
     * @Get("/{request_id}", name="admin_rest_events", options={"expose"=true})
     */
    public function getAllEventsAction($request_id)
    {
        $context = SerializationContext::create()->setGroups(array('administration_order_show'));
        $em = $this->getDoctrine()->getManager();

        $events = $em->getRepository('Celsius3CoreBundle:Event\\Event')->findBy(array('request' => $request_id));
        $requests = $em->getRepository('Celsius3CoreBundle:Request')->findBy(array('previousRequest' => $request_id));

        $requestsIds = array_map(function (\Celsius3\CoreBundle\Entity\Request $item) {
            return $item->getId();
        }, $requests);
        $remoteEvents = $em->getRepository('Celsius3CoreBundle:Event\\MultiInstanceEvent')
            ->getRemoteEvents($requestsIds);

        $all = array_merge($events, $remoteEvents);
        $keys = array_map(function (Event $e) {
            return $e->getId();
        }, $all);

        $reclaimEvents = $em->getRepository('Celsius3CoreBundle:Event\\ReclaimEvent')
            ->getReclaimEventsFor($keys);

        foreach ($reclaimEvents as $e) {
            if (!in_array($e->getId(), $keys)) {
                $all[] = $e;
            }
        }

        $view = $this->view(array_values($all), 200)->setFormat('json');
        $view->setSerializationContext($context);

        return $this->handleView($view);
    }

    /**
     * GET Route annotation.
     *
     * @Get("/{request_id}/{event}", name="admin_rest_event", options={"expose"=true})
     */
    public function getEventsAction($request_id, $event)
    {
        $events = $this->get('celsius3_core.event_manager')->getEvents($event, $request_id);

        $view = $this->view(array_values($events), 200)->setFormat('json');

        return $this->handleView($view);
    }

    /**
     * GET Route annotation.
     *
     * @Get("/{id}/get", name="admin_rest_event_get", options={"expose"=true})
     */
    public function getEventAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $event = $em->getRepository('Celsius3CoreBundle:Event')
            ->find($id);

        if (!$event) {
            throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.entity_not_found.event');
        }

        $view = $this->view($event, 200)->setFormat('json');

        return $this->handleView($view);
    }

    /**
     * @Post("/update_observations/{id}", name="admin_rest_event_update_observations", options={"expose"=true})
     *
     * @param $id
     */
    public function updateObservationsAction(Request $request, $id)
    {
        /** @var EntityManager */
        $em = $this->get('doctrine.orm.entity_manager');

        /** @var Event */
        $event = $em->getRepository('Celsius3CoreBundle:Event\Event')->find($id);
        if (!$event) {
            throw Exception::create(Exception::ENTITY_NOT_FOUND);
        }

        $event->setObservations($request->request->get('observations'));

        $em->persist($event);
        $em->flush($event);

        return $this->handleView($this->view(['updated' => true], 200)->setFormat('json'));
    }

    /**
     * @Post("/{request_id}/undo", name="admin_rest_order_event_undo", options={"expose"=true})
     */
    public function undoAction($request_id)
    {
        $em = $this->getDoctrine()->getManager();

        $request = $em->getRepository('Celsius3CoreBundle:Request')->find($request_id);

        if (!$request) {
            throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.entity_not_found.request');
        }

        if (!$request->getOperator()) {
            $request->setOperator($this->getUser());
        }

        $result = $this->get('celsius3_core.lifecycle_helper')->undoState($request);

        $view = $this->view($result, 200)->setFormat('json');

        return $this->handleView($view);
    }

    /**
     * @Post("/{request_id}/{event}", name="admin_rest_order_event", options={"expose"=true})
     */
    public function createEventAction($request_id, $event)
    {
        $em = $this->getDoctrine()->getManager();

        $request = $em->getRepository('Celsius3CoreBundle:Request')->find($request_id);

        if (!$request) {
            throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.entity_not_found.request');
        }

        if (!$request->getOperator()) {
            $request->setOperator($this->getUser());
        }

        $result = $this->get('celsius3_core.lifecycle_helper')->createEvent($event, $request, $this->getInstance());

        $view = $this->view($result, 200)->setFormat('json');

        $context = SerializationContext::create()->setGroups(array('administration_order_show'));
        $view->setSerializationContext($context);

        return $this->handleView($view);
    }
}
