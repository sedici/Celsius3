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
use Celsius3\Exception\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

final class UpdateEventObservationsPostController extends BaseInstanceDependentRestController
{
    private $entityManager;
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke(Request $request, $id)
    {
        $event = $this->findEvent($id);

        $event->setObservations($request->request->get('observations'));

        $this->entityManager->persist($event);
        $this->entityManager->flush();

        return $this->handleView($this->view(['updated' => true], 200)->setFormat('json'));
    }

    private function findEvent($id)
    {
        $event = $this->entityManager->getRepository(Event::class)->find($id);

        if (!$event) {
            throw Exception::create(Exception::ENTITY_NOT_FOUND);
        }

        return $event;
    }
}
