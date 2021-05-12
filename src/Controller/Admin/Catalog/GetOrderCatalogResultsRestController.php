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

namespace Celsius3\Controller\Admin\Catalog;

use Celsius3\CoreBundle\Controller\BaseInstanceDependentRestController;
use Celsius3\CoreBundle\Entity\Catalog;
use Celsius3\CoreBundle\Entity\Event\Event;
use Celsius3\CoreBundle\Entity\JournalType;
use Celsius3\CoreBundle\Entity\Order;
use Celsius3\CoreBundle\Exception\Exception;
use Doctrine\ORM\EntityManagerInterface;
use JMS\DiExtraBundle\Annotation as DI;
use JMS\Serializer\SerializationContext;

final class GetOrderCatalogResultsRestController extends BaseInstanceDependentRestController
{
    private $catalogRepository;
    private $orderRepository;
    private $eventRepository;

    /**
     * @DI\InjectParams({
     *     "entityManager" = @DI\Inject("doctrine.orm.entity_manager"),
     * })
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->catalogRepository = $entityManager->getRepository(Catalog::class);
        $this->orderRepository = $entityManager->getRepository(Order::class);
        $this->eventRepository = $entityManager->getRepository(Event::class);
    }

    public function __invoke($order_id)
    {
        $context = SerializationContext::create()->setGroups(['administration_order_show']);

        $order = $this->orderRepository->find($order_id);

        if (!$order) {
            throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.entity_not_found.order');
        }

        if ($order->getMaterialData() instanceof JournalType) {
            if ($order->getMaterialData()->getJournal()) {
                $title = $order->getMaterialData()->getJournal()->getName();
            } else {
                $title = $order->getMaterialData()->getOther();
            }
        } else {
            $title = $order->getMaterialData()->getTitle();
        }

        $catalogs = $this->catalogRepository
            ->findForInstanceAndGlobal($this->getInstance(), $this->getDirectory())
            ->select('c.id')
            ->getQuery()
            ->execute();

        $response = [
            'results' => $this->catalogRepository
                ->getCatalogResults($catalogs, $title),
            'searches' => $this->eventRepository
                ->findSimilarSearches($order, $this->getInstance()),
        ];

        $view = $this->view($response, 200)->setFormat('json');
        $view->setSerializationContext($context);

        return $this->handleView($view);
    }
}
