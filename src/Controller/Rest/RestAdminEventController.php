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

declare(strict_types=1);

namespace Celsius3\Controller\Rest;

use Celsius3\Controller\Base\EventController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;


// IsGranted('ROLE_ADMIN')
#[
    Route('/rest/v1/admin/event'),
]
final class RestAdminEventController extends EventController
{

    #[Route(
        "/{request_id}",
        name: "admin_rest_events",
        options: ['expose' => true],
        methods: ['GET'],
    )]
    public function restIndex(string $request_id): Response
    {
        return $this->restRenderer->render(
            $this->getAllEvents($request_id),
            serializerGroups: 'administration_order_show'
        );
    }


    private function handleEvent(
        string $request_id,
        callable $eventHandler
    ): Response {
        $request = $this->findRequest($request_id);
        $result = $eventHandler($request, $this->instance);
        return $this->restRenderer->render(
            $result, serializerGroups: 'administration_order_show'
        );
    }


    #[Route(
        "/{request_id}/annul",
        name: "admin_rest_order_annul_event",
        methods: ['POST'],
        options: ['expose' => true]
    )]
    public function createAnnulEvent(string $request_id): Response
    {
        return $this->handleEvent(
            $request_id,
            [$this->lifecycleHelper, 'createAnnulEvent']
        );
    }


    #[Route(
        "/{request_id}/approve",
        name: "admin_rest_order_approve_event",
        methods: ['POST'],
        options: ['expose' => true]
    )]
    public function createApproveEvent(string $request_id): Response
    {
        return $this->handleEvent(
            $request_id,
            [$this->lifecycleHelper, 'createApproveEvent']
        );
    }


    #[Route(
        "/{request_id}/cancel",
        name: "admin_rest_order_cancel_event",
        methods: ['POST'],
        options: ['expose' => true]
    )]
    public function createCancelEvent(string $request_id): Response
    {
        return $this->handleEvent(
            $request_id,
            [$this->lifecycleHelper, 'createCancelEvent']
        );
    }


    #[Route(
        "/{request_id}/creation",
        name: "admin_rest_order_creation_event",
        methods: ['POST'],
        options: ['expose' => true]
    )]
    public function createCreationEvent(string $request_id): Response
    {
        return $this->handleEvent(
            $request_id,
            [$this->lifecycleHelper, 'createCreationEvent']
        );
    }


    #[Route(
        "/{request_id}/deliver",
        name: "admin_rest_order_deliver_event",
        methods: ['POST'],
        options: ['expose' => true]
    )]
    public function createDeliverEvent(string $request_id): Response
    {
        return $this->handleEvent(
            $request_id,
            [$this->lifecycleHelper, 'createDeliverEvent']
        );
    }


    #[Route(
        "/{request_id}/receive",
        name: "admin_rest_order_receive_event",
        methods: ['POST'],
        options: ['expose' => true]
    )]
    public function createReceiveEvent(string $request_id): Response
    {
        return $this->handleEvent(
            $request_id,
            [$this->lifecycleHelper, 'createReceiveEvent']
        );
    }


    #[Route(
        "/{request_id}/search",
        name: "admin_rest_order_search_event",
        methods: ['POST'],
        options: ['expose' => true],
    )]
    public function createSearchEvent(string $request_id): Response
    {
        return $this->handleEvent(
            $request_id,
            [$this->lifecycleHelper, 'createSearchEvent']
        );
    }


    #[Route(
        "/{request_id}/request",
        name: "admin_rest_order_request_event",
        methods: ['POST'],
        options: ['expose' => true]
    )]
    public function createRequestEvent(string $request_id): Response
    {
        $request = $this->findRequest($request_id);
        $data = $this->lifecycleHelper->createRequestEvent($request, $this->instance);
        throw new \Exception((string)var_dump($data));
        $this->restRenderer->render(
            $data,
            serializerGroups: 'administration_order_show'
        );

        return $this->handleEvent(
            $request_id,
            [$this->lifecycleHelper, 'createRequestEvent']
        );
    }


    #[Route(
        "/{request_id}/take",
        name: "admin_rest_order_take_event",
        methods: ['POST'],
        options: ['expose' => true]
    )]
    public function createTakeEvent(string $request_id): Response
    {
        return $this->handleEvent(
            $request_id,
            [$this->lifecycleHelper, 'createTakeEvent']
        );
    }


    #[Route(
        "/{request_id}/undo",
        name: "admin_rest_order_undo_event",
        methods: ['POST'],
        options: ['expose' => true]
    )]
    public function createUndoEvent(string $request_id): Response
    {
        return $this->handleEvent(
            $request_id,
            [$this->lifecycleHelper, 'undoState']
        );
    }


    #[Route(
        "/{id}/get",
        name: "admin_rest_event_get",
        options: ['expose' => true]
    )]
    public function showEvent(string $id): Response
    {
        return $this->restRenderer->show(
            $id, 'administration_order_show'
        );
    }


    #[Route(
        "/update_observations/{event_id}",
        name: "admin_rest_event_update_observations",
        options: ['expose' => true],
        methods: ['POST']
    )]
    public function eventUpdateObservations(string $event_id): Response
    {
        $request = $this->requestStack->getCurrentRequest();
        $reqData = $request->toArray();

        $event = $this->findEvent($event_id);

        $event->setObservations($reqData['observations']);

        $this->entityManager->persist($event);
        $this->entityManager->flush();

        return $this->restRenderer->render(
            $event,
            serializerGroups: 'administration_order_show'
        );
    }


    #[Route(
        "/{request_id}/{event_type}",
        name: "admin_rest_event",
        options: ['expose' => true],
        methods: ['GET'],
    )]
    public function eventByType(string $request_id, string $event_type): Response
    {
        return $this->restRenderer->render(
            $this->eventManager->getEvents($event_type, $request_id),
            serializerGroups: 'administration_order_show'
        );
    }


    #[Route(
        "/{request_id}/{event_id}/create",
        name: "admin_rest_order_event",
        options: ['expose' => true],
        methods: ['POST'],
    )]
    public function createEvent(string $request_id, string $event_id)
    {
        $request = $this->findRequest($request_id);

        $result = $this->lifecycleHelper->createEvent($event_id, $request, $this->instance);

        return $this->restRenderer->render(
            $result,
            serializerGroups: 'administration_order_show'
        );
    }
}