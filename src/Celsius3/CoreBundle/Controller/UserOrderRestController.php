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

namespace Celsius3\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations\Get;
use JMS\Serializer\SerializationContext;

/**
* User controller.
*
* @Route("/user/rest/orders")
*/
class UserOrderRestController extends BaseInstanceDependentRestController
{

    /**
    * GET Route annotation.
    * @Get("", name="user_rest_order", options={"expose"=true})
    */
    public function getOrdersAction(Request $request)
    {
        $context = SerializationContext::create()->setGroups(array('user_list'));

        $withRequest = $request->query->get('withRequest', false);

        $states = explode(',', $request->query->get('state', ''));

        $orders = $this->getDoctrine()->getManager()
        ->getRepository('Celsius3CoreBundle:Order')
        ->findForInstance($this->getInstance(), null, $states, $this->getUser());

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($orders, $this->get('request')->query->get('page', 1)/* page number */, $this->getResultsPerPage()/* limit per page */)->getItems();

        if ($withRequest) {
            $requests = $this->getDoctrine()->getManager()
            ->getRepository('Celsius3CoreBundle:Request')
            ->createQueryBuilder('r')
            ->select('r, s, ow')
            ->join('r.states', 's')
            ->join('r.owner', 'ow')
            ->where('r.order IN (:orders)')
            ->andWhere('s.isCurrent = true')
            ->setParameter('orders', array_map(function (\Celsius3\CoreBundle\Entity\Order $order) {
                return $order->getId();
            }, $pagination))
            ->getQuery()->getResult();

            $response = array(
                'orders' => array_values($pagination),
                'requests' => array_column(array_map(function(\Celsius3\CoreBundle\Entity\Request $request) {
                    return array(
                        'id' => $request->getOrder()->getId(),
                        'request' => $request,
                    );
                }, $requests), 'request', 'id'),
            );

            $view = $this->view($response, 200)->setFormat('json');
        } else {
            $view = $this->view(array_values($pagination), 200)->setFormat('json');
        }
        $view->setSerializationContext($context);

        return $this->handleView($view);
    }

    /**
    * GET Route annotation.
    * @Get("/count", name="user_rest_order_count_get", options={"expose"=true})
    */
    public function getOrderCountAction(Request $request)
    {
        $user = $this->getUser();

        $orderCount = $this->getDoctrine()->getManager()
        ->getRepository('Celsius3CoreBundle:State')
        ->countUserOrders($this->getInstance(), $user);

        $view = $this->view($orderCount, 200)->setFormat('json');

        return $this->handleView($view);
    }

    /**
    * GET Route annotation.
    * @Get("/{id}", name="user_rest_order_get", options={"expose"=true})
    */
    public function getOrderAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $order = $em->getRepository('Celsius3CoreBundle:Order')->find($id);

        if (!$order) {
            return $this->createNotFoundException('Order not found.');
        }

        $view = $this->view($order, 200)->setFormat('json');

        return $this->handleView($view);
    }

}
