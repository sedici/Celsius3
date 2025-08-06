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

namespace Celsius3\Controller\Api;

use Celsius3\Controller\Base\OrderController;
use Celsius3\Entity\BaseUser;
use Celsius3\Entity\Order;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations\Get;
use Symfony\Component\HttpFoundation\Request;
use Celsius3\Exception\Exception;

/**
 * User controller.
 */
#[Route('/api/orders')]
class RestOrderController extends OrderController
{

    /**
     * GET Route annotation.
     */
    #[Get('/received_at_update')]
    public function receivedAtUpdateAction()
    {
        $request = $this->requestStack->getCurrentRequest();
        $order = $this->repository->find($request->query->get('order_id'));

        $view = $this->restRenderer->render(
            data: $order->getReceivedAt(), statusCode: 200
        )->setFormat('json');
        return $this->handleView($view);
    }

    /**
     * GET Route annotation.
     */
    #[Get('/{token}')]
    public function ordersAction($token)
    {
        $accessToken = $this->getAccessTokenByToken($token);
        $isValidToken = $this->validateAccessToken($accessToken);

        $orders = [];
        if ($isValidToken) {
            $em = $this->entityManager;

            $user = $accessToken->getUser();

            if (!$user) {
                throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.entity_not_found.user');
            }

            $orders = $em->getRepository(Order::class)
                    ->findBy([
                'owner' => $user->getId(),
                'instance' => $user->getInstance(),
            ]);
        }

        $view = $this->view($orders, 200)->setFormat('json');

        $context = new Context();
        $context->addGroup('api');
        $view->setContext($context);

        return $this->handleView($view);
    }

    /**
     * GET Route annotation.
     */
    #[Get('/state/{state}')]
    public function ordersByStateAction($state, Request $request)
    {
        $accessToken = $this->getAccessTokenByToken($request->get('access_token'));
        $isValidToken = $this->validateAccessToken($accessToken);

        $orders = [];
        if ($isValidToken) {
            $em = $this->entityManager;

            $startDate = $request->query->get('startDate');
            $limit = $request->query->get('limit');
            $offset = $request->query->get('offset');

            $orders = $em->getRepository(Order::class)
                    ->findOrdersByStateType($state, $startDate, null, $this->getInstance(), $limit, $offset);
        }

        $view = $this->view($orders, 200)->setFormat('json');

        $context = new Context();
        $context->addGroup('api');
        $view->setContext($context);

        return $this->handleView($view);
    }

    /**
     * GET Route annotation.
     */
    #[Get('/{user_id}/state/{state}')]
    public function ordersByUserAndStateAction(Request $request, $user_id, $state)
    {
        $accessToken = $this->getAccessTokenByToken($request->get('access_token'));
        $isValidToken = $this->validateAccessToken($accessToken);

        $orders = [];
        if ($isValidToken) {
            $em = $this->entityManager;

            $user = $em->getRepository(BaseUser::class)
                    ->find($user_id);

            if (!$user) {
                throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.entity_not_found.user');
            }

            $orders = $em->getRepository(Order::class)
                    ->findOrdersByStateType($state, null, $user, $this->getInstance());
        }

        $view = $this->view($orders, 200)->setFormat('json');

        $context = new Context();
        $context->addGroup('api');
        $view->setContext($context);

        return $this->handleView($view);
    }

}
