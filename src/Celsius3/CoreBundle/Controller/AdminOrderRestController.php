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

/**
 * User controller.
 *
 * @Route("/admin/rest/orders")
 */
class AdminOrderRestController extends BaseInstanceDependentRestController
{

    /**
     * GET Route annotation.
     * @Get("", name="admin_rest_order", options={"expose"=true})
     */
    public function getOrdersAction(Request $request)
    {
        if ($request->query->get('type', null) === 'mine') {
            $user = $this->getUser();
        } else {
            $user = null;
        }

        $state = $request->query->get('state', null);

        $orders = $this->getDocumentManager()
                ->getRepository('Celsius3CoreBundle:Order')
                ->findForInstance($this->getInstance(), $user, $state);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($orders, $this->get('request')->query->get('page', 1)/* page number */, $this->getResultsPerPage()/* limit per page */)->getItems();

        $view = $this->view(array_values($pagination), 200)
                ->setFormat('json');

        return $this->handleView($view);
    }

    /**
     * GET Route annotation.
     * @Get("/count", name="admin_rest_order_count_get", options={"expose"=true})
     */
    public function getOrderCountAction(Request $request)
    {
        if ($request->query->get('type', null) === 'mine') {
            $user = $this->getUser();
        } else {
            $user = null;
        }

        $orderCount = $this->getDocumentManager()
                ->getRepository('Celsius3CoreBundle:State')
                ->countOrders($this->getInstance(), $user);
        
        $view = $this->view($orderCount, 200)
                ->setFormat('json');

        return $this->handleView($view);
    }

    /**
     * GET Route annotation.
     * @Get("/get", name="admin_rest_order_request_get", options={"expose"=true})
     */
    public function getOrdersAndRequestsAction(Request $request)
    {
        if ($request->query->get('type', null) === 'mine') {
            $user = $this->getUser();
        } else {
            $user = null;
        }

        $state = $request->query->get('state', null);
        $orderType = $request->query->get('orderType', null);

        $states = $this->getDocumentManager()
                ->getRepository('Celsius3CoreBundle:Order')
                ->findForInstance($this->getInstance(), $user, $state, null, $orderType);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($states, $this->get('request')->query->get('page', 1)/* page number */, $this->getResultsPerPage()/* limit per page */)->getItems();

        $orders = $this->getDocumentManager()
                ->getRepository('Celsius3CoreBundle:Order')
                ->createQueryBuilder()
                ->field('id')->in(array_column(array_column($pagination, 'order'), '$id'))
                ->getQuery()
                ->execute()
                ->toArray();

        $requests = $this->getDocumentManager()
                ->getRepository('Celsius3CoreBundle:Request')
                ->createQueryBuilder()
                ->field('order.id')->in(array_column(array_column($pagination, 'order'), '$id'))
                ->getQuery()
                ->execute()
                ->toArray();

        $response = array(
            'orders' => array_values($orders),
            'requests' => array_column(array_map(function($request) {
                                return array(
                                    'id' => $request->getOrder()->getId(),
                                    'request' => $request,
                                );
                            }, $requests), 'request', 'id'),
                );

                $view = $this->view($response, 200)
                        ->setFormat('json');

                return $this->handleView($view);
            }

            /**
             * GET Route annotation.
             * @Get("/{id}", name="admin_rest_order_get", options={"expose"=true})
             */
            public function getOrderAction($id)
            {
                $dm = $this->getDocumentManager();

                $order = $dm->getRepository('Celsius3CoreBundle:Order')
                        ->find($id);

                if (!$order) {
                    return $this->createNotFoundException('Order not found.');
                }

                $view = $this->view($order, 200)
                        ->setFormat('json');

                return $this->handleView($view);
            }
        }        
