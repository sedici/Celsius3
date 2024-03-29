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

namespace Celsius3\CoreBundle\Controller\User\Order;

use Celsius3\CoreBundle\Controller\OrderController;
use Celsius3\CoreBundle\Entity\Order;
use Celsius3\CoreBundle\Exception\Exception;
use Doctrine\ORM\EntityManagerInterface;
use JMS\DiExtraBundle\Annotation as DI;

final class ShowUserOrderViewController extends OrderController
{
    private $orderRepository;

    /**
     * @DI\InjectParams({
     *     "entityManager" = @DI\Inject("doctrine.orm.entity_manager")
     * })
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->orderRepository = $entityManager->getRepository(Order::class);
    }

    public function __invoke($id)
    {
        $order = $this->findOrder($id);

        return $this->render(
            'Celsius3CoreBundle:UserOrder:show.html.twig',
            [
                'entity' => $order,
            ]
        );
    }

    private function findOrder($id): Order
    {
        $order = $this->orderRepository->findUserOrder($id, $this->getInstance(), $this->getUser());

        if (!$order) {
            throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.entity_not_found.Order');
        }

        return $order;
    }
}
