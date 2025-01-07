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

use Celsius3\Entity\Order;
use Celsius3\Form\Type\Filter\OrderFilterType;
use Celsius3\Helper\ConfigurationHelper;
use Celsius3\Helper\InstanceHelper;
use Celsius3\Manager\FilterManager;
use Celsius3\Repository\OrderRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;

final class ListUserOrdersViewController extends AbstractController
{
    private $orderRepository;
    private $instanceHelper;
    private $filterManager;
    private $configurationHelper;
    private $paginator;
    public function __construct(
        OrderRepositoryInterface $orderRepository,
        InstanceHelper $instanceHelper,
        FilterManager $filterManager,
        ConfigurationHelper $configurationHelper,
        PaginatorInterface $paginator
    ) {
        $this->orderRepository = $orderRepository;
        $this->instanceHelper = $instanceHelper;
        $this->filterManager = $filterManager;
        $this->configurationHelper = $configurationHelper;
        $this->paginator = $paginator;
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

        $paginator = $this->paginator;
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
