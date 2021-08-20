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

namespace Celsius3\Controller\User\Order;

use Celsius3\CoreBundle\Entity\Order;
use Celsius3\CoreBundle\Form\Type\Filter\OrderFilterType;
use Celsius3\CoreBundle\Helper\ConfigurationHelper;
use Celsius3\CoreBundle\Helper\InstanceHelper;
use Celsius3\CoreBundle\Manager\FilterManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

final class ListUserOrdersViewController extends AbstractController
{
    private $orderRepository;
    private $instanceHelper;
    private $filterManager;
    private $configurationHelper;

    public function __construct(
        EntityManagerInterface $entityManager,
        InstanceHelper $instanceHelper,
        FilterManager $filterManager,
        ConfigurationHelper $configurationHelper
    ) {
        $this->orderRepository = $entityManager->getRepository(Order::class);
        $this->instanceHelper = $instanceHelper;
        $this->filterManager = $filterManager;
        $this->configurationHelper = $configurationHelper;
    }

    public function __invoke(Request $request)
    {
        $filter_form = $this->createForm(
            OrderFilterType::class,
            null,
            [
                'instance' => $this->instanceHelper->getSessionInstance(),
                'owner' => $this->getUser(),
            ]
        );

        $query = $this->orderRepository->listUserOrdersQuery(
            $this->instanceHelper->getSessionInstance(),
            $this->getUser()
        );

        if ($filter_form !== null) {
            $filter_form = $filter_form->handleRequest($request);
            $query = $this->filterManager->filter($query, $filter_form, Order::class);
        }

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->get('page', 1),
            $this->configurationHelper->getCastedValue(
                $this->instanceHelper->getSessionInstance()->get('results_per_page')
            ),
            [
                'defaultSortFieldName' => 'e.updatedAt',
                'defaultSortDirection' => 'desc',
            ]
        );

        return $this->render(
            'User/Order/index.html.twig',
            [
                'pagination' => $pagination,
                'filter_form' => ($filter_form !== null) ? $filter_form->createView() : $filter_form,
            ]
        );
    }
}