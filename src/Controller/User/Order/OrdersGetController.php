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

namespace Celsius3\Controller\User\Order;

use Celsius3\Entity\Order;
use Celsius3\Helper\ConfigurationHelper;
use Celsius3\Helper\InstanceHelper;
use Celsius3\Repository\OrderRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\View\ViewHandlerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;

final class OrdersGetController extends AbstractFOSRestController
{
    private $orderRepository;
    private $instanceHelper;
    private $security;
    private $configurationHelper;
    private $paginator;
    private $requestRepository;
    private $viewHandler;

    public function __construct(
        EntityManagerInterface $entityManager,
        OrderRepositoryInterface $orderRepository,
        InstanceHelper $instanceHelper,
        Security $security,
        ConfigurationHelper $configurationHelper,
        PaginatorInterface $paginator,
        ViewHandlerInterface $viewHandler
    ) {
        $this->orderRepository = $orderRepository;
        $this->requestRepository = $entityManager->getRepository(\Celsius3\Entity\Request::class);
        $this->instanceHelper = $instanceHelper;
        $this->security = $security;
        $this->configurationHelper = $configurationHelper;
        $this->paginator = $paginator;
        $this->viewHandler = $viewHandler;
    }

    public function __invoke(Request $request): Response
    {
        $with_request = $request->query->get('withRequest', false);

        $states = explode(',', $request->query->get('state', ''));

        $orders = $this->orderRepository->findForInstance(
            $instance = $this->instanceHelper->getSessionInstance(),
            null,
            $states,
            $this->security->getUser()
        );

        $pagination = $this->paginator->paginate(
            $orders,
            (int) $request->query->get('page', 1),
            (int) $this->configurationHelper->getCastedValue($instance->get('results_per_page'))
        )->getItems();

        if ($with_request) {
            $requests = $this->requestRepository->findByOrders(
                    array_map(
                        function (Order $order) {
                            return $order->getId();
                        },
                        $pagination
                    )
                );

            $response = array(
                'orders' => array_values($pagination),
                'requests' => array_column(
                    array_map(
                        function (\Celsius3\Entity\Request $request) {
                            return array(
                                'id' => $request->getOrder()->getId(),
                                'request' => $request,
                            );
                        },
                        $requests
                    ),
                    'request',
                    'id'
                ),
            );

            $view = $this->view($response, 200)->setFormat('json');
        } else {
            $view = $this->view(array_values($pagination), 200)->setFormat('json');
        }

        $context = new Context();
        $context->addGroup('user_list');
        $view->setContext($context);

        return $this->viewHandler->handle($view);
    }
}
