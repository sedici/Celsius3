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

namespace Celsius3\Controller\Admin\Event;

use Celsius3\Controller\BaseInstanceDependentRestController;
use Celsius3\Entity\Event\Event;
use Celsius3\Entity\Event\MultiInstanceEvent;
use Celsius3\Entity\Event\ReclaimEvent;
use Celsius3\Entity\Request;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializationContext;

final class AllRequestEventsGetController extends BaseInstanceDependentRestController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke($request_id)
    {
        $context = SerializationContext::create()->setGroups(['administration_order_show']);

        $all = $this->allEvents($request_id);

        $view = $this->view(array_values($all), 200)->setFormat('json');
        $view->setSerializationContext($context);

        return $this->handleView($view);
    }

    private function allEvents($request_id): array
    {
        $events = $this->events($request_id);
        $remote_events = $this->remoteEvents($request_id);

        $all = array_merge($events, $remote_events);

        return $this->addReclaimEvents($all);
    }

    private function events($request_id)
    {
        $events = $this->entityManager->getRepository(Event::class)
            ->findBy(['request' => $request_id]);
        return $events;
    }

    private function remoteEvents($request_id)
    {
        $requests = $this->entityManager->getRepository(Request::class)
            ->findBy(['previousRequest' => $request_id]);
        $requests_ids = array_map(
            function (Request $item) {
                return $item->getId();
            },
            $requests
        );
        $remote_events = $this->entityManager->getRepository(MultiInstanceEvent::class)
            ->getRemoteEvents($requests_ids);
        return $remote_events;
    }

    private function addReclaimEvents(array $all): array
    {
        $keys = array_map(
            function (Event $e) {
                return $e->getId();
            },
            $all
        );
        $reclaim_events = $this->entityManager->getRepository(ReclaimEvent::class)
            ->getReclaimEventsFor($keys);

        foreach ($reclaim_events as $e) {
            if (!in_array($e->getId(), $keys)) {
                $all[] = $e;
            }
        }
        return $all;
    }
}
