<?php

/*
 * Celsius3 - Order management
 * Copyright (C) 2014 PrEBi <info@prebi.unlp.edu.ar>
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

namespace Celsius3\ApiBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations\Get;
use Symfony\Component\HttpFoundation\Request;

/**
 * User controller.
 *
 * @Route("/orders")
 */
class OrderController extends BaseController
{

    /**
     * GET Route annotation.
     * @Get("/{user_id}")
     */
    public function ordersAction($user_id)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('Celsius3CoreBundle:BaseUser')
                ->find($user_id);

        if (!$user) {
            return $this->createNotFoundException('User not found.');
        }

        $orders = $em->getRepository('Celsius3CoreBundle:Order')
                ->findBy(array(
            'owner' => $user->getId(),
            'instance' => $this->getInstance()->getId(),
        ));

        $view = $this->view($orders, 200)
                ->setFormat('json');

        return $this->handleView($view);
    }

    /**
     * GET Route annotation.
     * @Get("/state/{state}")
     */
    public function ordersByStateAction($state, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $startDate = $request->query->get('startDate');

        $orders = $em->getRepository('Celsius3CoreBundle:Order')
                ->findByStateType($state, $startDate, null, $this->getInstance());

        $view = $this->view($orders, 200)
                ->setFormat('json');

        return $this->handleView($view);
    }

    /**
     * GET Route annotation.
     * @Get("/{user_id}/state/{state}")
     */
    public function ordersByUserAndStateAction($user_id, $state)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('Celsius3CoreBundle:BaseUser')
                ->find($user_id);

        if (!$user) {
            return $this->createNotFoundException('User not found.');
        }

        $orders = $em->getRepository('Celsius3CoreBundle:Order')
                ->findByStateType($state, $user, $this->getInstance());

        $view = $this->view($orders, 200)
                ->setFormat('json');

        return $this->handleView($view);
    }
}
