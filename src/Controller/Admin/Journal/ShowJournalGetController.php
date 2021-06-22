<?php

declare(strict_types=1);

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

namespace Celsius3\Controller\Admin\Journal;

use Celsius3\CoreBundle\Controller\BaseInstanceDependentController;
use Celsius3\CoreBundle\Entity\Event\Event;
use Celsius3\CoreBundle\Entity\Journal;
use Celsius3\CoreBundle\Exception\Exception;
use Celsius3\CoreBundle\Manager\CatalogManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class ShowJournalGetController extends BaseInstanceDependentController
{
    private $journalRepository;
    private $eventRepository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->journalRepository = $entityManager->getRepository(Journal::class);
        $this->eventRepository = $entityManager->getRepository(Event::class);
    }

    public function __invoke(Request $request, $id): Response
    {
        $entity = $this->journalRepository->findOneForInstanceOrGlobal(
            $this->getInstance(),
            $this->getDirectory(),
            $id
        );
        if (!$entity) {
            throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.entity_not_found.journal');
        }
        $receptions = $this->eventRepository->getPreviousJournalReceivedRequests($this->getInstance(), $entity);
        $results = $this->eventRepository->getPreviousJournalSearches($this->getInstance(), $entity);

        $searches = [
            CatalogManager::CATALOG__FOUND => [],
            CatalogManager::CATALOG__PARTIALLY_FOUND => [],
        ];
        foreach ($results as $search) {
            $searches[$search->getResult()][] = $search;
        }

        return $this->render(
            'Admin/Journal/show.html.twig',
            [
                'entity' => $entity,
                'searches' => $searches,
                'receptions' => $receptions,
            ]
        );
    }
}
