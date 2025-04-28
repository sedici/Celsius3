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

namespace Celsius3\Controller\Base;

use Celsius3\Controller\Core\EntityController;
use Celsius3\Entity\Catalog;
use Celsius3\Entity\Event\Event;
use Celsius3\Entity\JournalType;
use Celsius3\Entity\Order;
use Celsius3\Exception\Exception;

class CatalogController extends EntityController
{

    protected $catalogRepository;
    protected $orderRepository;
    protected $eventRepository;



    public function initialize(): void
    {
        $this->setEntity(Catalog::class);

        parent::initialize();

        $this->catalogRepository = $this->entityManager->getRepository(Catalog::class);
        $this->orderRepository = $this->entityManager->getRepository(Order::class);
        $this->eventRepository = $this->entityManager->getRepository(Event::class);

        $this->setInstanceDependent(true);
        $this->setSortDefaults([
            'defaultSortFieldName' => 'e.name',
            'defaultSortDirection' => 'asc',
        ]);
    }


    protected function getRestCatallogs(): array
    {
        return $this->repository
            ->findForInstanceAndGlobalWithoutDisabled(
                $this->instance,
                $this->directory
            )
            ->getQuery()
            ->execute();
    }


    protected function orderCatalogResults(string $order_id): array
    {
        $order = $this->orderRepository->find($order_id);

        if (!$order) $this->error(
            Exception::ENTITY_NOT_FOUND,
            Order::class,
        );

        $title = ($order->getMaterialData() instanceof JournalType)
            ? (($order->getMaterialData()->getJournal())
                ? $order->getMaterialData()->getJournal()->getName()
                : $order->getMaterialData()->getOther())
            : $order->getMaterialData()->getTitle();

        $catalogs = $this->getRestCatallogs();

        $response = [
            'results' => $this->catalogRepository
                ->getCatalogResults($catalogs, $title),
            'searches' => $this->eventRepository
                ->findSimilarSearches($order, $this->getInstance()),
        ];

        return $response;
    }
}