<?php

/*
 * Celsius3 - User order management
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

 namespace Celsius3\Controller\Rest;

use Celsius3\Controller\Base\OrderController;
use Celsius3\Entity\Order;
use Celsius3\Entity\Request;
use Celsius3\Entity\State;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

 final class RestUserOrderController extends OrderController
{

    #[Route(
        '/user/rest/orders',
        name: 'user_rest_order',
        methods: ['GET'],
        options: ['expose' => true]
    )]
    public function ordersGet(): Response
    {
        $request = $this->requestStack->getCurrentRequest();

        $orders = $this->repository->findForInstance(
            $this->instance,
            null,
            explode(
                ',', $request->get('state', '')
            ),
            $this->getUser()
        );

        $pagination = $this->paginate($orders)->getItems();

        if ($request->get('withRequest', false)) {
            $requests = $this->entityManager
                ->getRepository(Request::class)
                ->findByOrders(
                    array_map(
                        fn (Order $order): mixed => $order->getId(),
                        $pagination
                    )
                );

            $response = [
                'orders' => array_values($pagination),
                'requests' => array_column(
                    array_map(
                        fn (Request $request): array => [
                            'id' => $request->getOrder()->getId(),
                            'request' => $request,
                        ],
                        $requests
                    ),
                    'request',
                    'id'
                ),
            ];

            return $this->restRenderer->render(
                $response,
                serializerGroups: 'user_list'
            );
        }
        
        return $this->restRenderer->render(
            array_values($pagination),
            serializerGroups: 'user_list'
        );
    }


    #[Route(
        '/user/rest/orders/count',
        name: 'user_rest_order_count_get',
        methods: ['GET'],
        options: ['expose' => true]
    )]
    public function orderCount(): Response
    {
        $user = $this->security->getUser();
        $stateRepository = $this->entityManager->getRepository(State::class);
        $order_count = $stateRepository->countUserOrders(
            $this->instanceHelper->getSessionInstance(),
            $user
        );
        return $this->restRenderer->render($order_count);
    }


    #[Route(
        '/user/rest/orders/{id}',
        name: 'user_rest_order_get',
        methods: ['GET'],
        options: ['expose' => true]
    )]
    public function getOrder(string $id): Response
    { return $this->restRenderer->show($id); }
}