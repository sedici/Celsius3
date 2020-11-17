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
use Celsius3\CoreBundle\Form\Type\Filter\OrderFilterType;
use Doctrine\ORM\EntityManagerInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\HttpFoundation\Request;

final class ListUserOrdersViewController extends OrderController
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

    public function __invoke(Request $request)
    {
        $filter_form = $this->createForm(
            OrderFilterType::class,
            null,
            [
                'instance' => $this->getInstance(),
                'owner' => $this->getUser(),
            ]
        );

        $query = $this->orderRepository->listUserOrdersQuery($this->getInstance(), $this->getUser());
        if ($filter_form !== null) {
            $filter_form = $filter_form->handleRequest($request);
            $query = $this->filter('Order', $filter_form, $query);
        }

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->get('page', 1)
            /* page number */,
            $this->getResultsPerPage()
            /* limit per page */,
            $this->getSortDefaults()
        );

        return $this->render(
            'Celsius3CoreBundle:UserOrder:index.html.twig',
            [
                'pagination' => $pagination,
                'filter_form' => ($filter_form !== null) ? $filter_form->createView() : $filter_form,
            ]
        );
    }
}
