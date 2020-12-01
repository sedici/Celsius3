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

namespace Celsius3\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations\Get;
use JMS\Serializer\SerializationContext;
use Celsius3\CoreBundle\Exception\Exception;

/**
 * User controller.
 *
 * @Route("/admin/rest/orders")
 */
class AdminOrderRestController extends BaseInstanceDependentRestController
{
    protected function getSortDefaults()
    {
        return array(
            'defaultSortFieldName' => 'o.updatedAt',
            'defaultSortDirection' => 'asc',
        );
    }

    /**
     * GET Route annotation.
     *
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

        $orders = $this->getDoctrine()->getManager()
                ->getRepository('Celsius3CoreBundle:Order')
                ->findForInstance($this->getInstance(), $user, $state);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($orders, $this->get('request_stack')->getCurrentRequest()->query->get('page', 1)/* page number */, $this->getResultsPerPage()/* limit per page */, $this->getSortDefaults())->getItems();

        $view = $this->view(array_values($pagination), 200)->setFormat('json');

        return $this->handleView($view);
    }

    /**
     * GET Route annotation.
     *
     * @Get("/count", name="admin_rest_order_count_get", options={"expose"=true})
     */
    public function getOrderCountAction(Request $request)
    {
        if ($request->query->get('type', null) === 'mine') {
            $user = $this->getUser();
        } elseif (is_int(intval($request->query->get('type', null)))) {
            $user = $this->getDoctrine()->getManager()->getRepository('Celsius3CoreBundle:BaseUser')
                    ->find(intval($request->query->get('type', null)));
            if (!is_null($user) && !$user->hasRole('ROLE_ADMIN') && !$user->hasRole('ROLE_SUPER_ADMIN')) {
                $user = null;
            }
        } else {
            $user = null;
        }

        $orderType = $request->query->get('orderType', null);
        if ($orderType === 'allTypes') {
            $orderType = null;
        }

        $orderCount = $this->getDoctrine()->getManager()
                ->getRepository('Celsius3CoreBundle:State')
                ->countOrders($this->getInstance(), $user, $orderType);

        $view = $this->view($orderCount, 200)->setFormat('json');

        return $this->handleView($view);
    }

    /**
     * GET Route annotation.
     *
     * @Get("/get", name="admin_rest_order_request_get", options={"expose"=true})
     */
    public function getOrdersAndRequestsAction(Request $request)
    {
        $context = SerializationContext::create()->setGroups(array('administration_list'));
        $em = $this->getDoctrine()->getManager();

        if ($request->query->get('type', null) === 'mine') {
            $user = $this->getUser();
        } elseif (is_int(intval($request->query->get('type', null)))) {
            $user = $em->getRepository('Celsius3CoreBundle:BaseUser')
                    ->find(intval($request->query->get('type', null)));
            if (!is_null($user) && !$user->hasRole('ROLE_ADMIN') && !$user->hasRole('ROLE_SUPER_ADMIN')) {
                $user = null;
            }
        } else {
            $user = null;
        }

        $state = $request->query->get('state', null);
        $orderType = $request->query->get('orderType', null);

        $states = $em->getRepository('Celsius3CoreBundle:Order')
                        ->findForInstance($this->getInstance(), $user, $state, null, $orderType);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($states, $request->query->get('page', 1)/* page number */, $this->getResultsPerPage()/* limit per page */)->getItems();

        $requests = $em->getRepository('Celsius3CoreBundle:Request')
                        ->findRequestForOrders($pagination);

        $response = array(
            'orders' => array_values($pagination),
            'requests' => array_column(
                    array_map(
                            function (\Celsius3\CoreBundle\Entity\Request $request) {
                                return array(
                                    'id' => $request->getOrder()->getId(),
                                    'request' => $request,
                                );
                            }, $requests), 'request', 'id'),
                );

        $view = $this->view($response, 200)->setFormat('json');
        $view->setSerializationContext($context);

        return $this->handleView($view);
    }

            /**
             * GET Route annotation.
             *
             * @Get("/{id}", name="admin_rest_order_get", options={"expose"=true})
             */
            public function getOrderAction($id)
            {
                $context = SerializationContext::create()->setGroups(array('administration_order_show'));

                $em = $this->getDoctrine()->getManager();

                $order = $em->getRepository('Celsius3CoreBundle:Order')->find($id);

                if (!$order) {
                    throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.entity_not_found.order');
                }

                $view = $this->view($order, 200)->setFormat('json');
                $view->setSerializationContext($context);

                return $this->handleView($view);
            }

            /**
             * @Get("/interaction/{id}", name="admin_rest_order_interaction", options={"expose"=true})
             */
            public function getInteractionAction($id)
            {
                $order = $this->getDoctrine()->getManager()->getRepository('Celsius3CoreBundle:Order')->find($id);
                $institution = $order->getOriginalRequest()->getOwner()->getInstitution();

                $instance = $this->getInstance();
                $interaction['result'] = false;

                if ($institution->getInstance() !== $instance) {
                    $institutionRepository = $this->getDoctrine()->getManager()->getRepository('Celsius3CoreBundle:Institution');
                    $baseInstitution = $institutionRepository->getBaseInstitution($institution);

                    $interaction['result'] = true;
                    $institutions = $this->getDoctrine()->getManager()->getRepository('Celsius3CoreBundle:Institution')->getInstitutionsTree($baseInstitution);

                    $requestRepository = $this->getDoctrine()->getManager()->getRepository('Celsius3CoreBundle:Request');
                    $response['institutionInteraction'] = $requestRepository->getInteractionOfInstitutionWithInstance($instance, $institutions);
                    $response['instanceInteraction'] = $requestRepository->getInteractionOfInstanceWithInstitution($instance, $institutions);

                    $interaction['institution'] = $baseInstitution->getName();
                    $interaction['instance'] = $instance->getName();

                    $interaction['institutionInteraction']['data']['created'] = 0;
                    $interaction['institutionInteraction']['data']['delivered'] = 0;
                    $interaction['institutionInteraction']['data']['annulled'] = 0;
                    $interaction['institutionInteraction']['data']['cancelled'] = 0;
                    foreach ($response['institutionInteraction'] as $res) {
                        $interaction['institutionInteraction']['data'][$res['st']] = $res['c'];
                    }

                    $interaction['instanceInteraction']['data']['created'] = 0;
                    $interaction['instanceInteraction']['data']['delivered'] = 0;
                    $interaction['instanceInteraction']['data']['annulled'] = 0;
                    $interaction['instanceInteraction']['data']['cancelled'] = 0;
                    foreach ($response['instanceInteraction'] as $res) {
                        $interaction['instanceInteraction']['data'][$res['st']] = $res['c'];
                    }
                }

                $view = $this->view($interaction, 200)->setFormat('json');

                return $this->handleView($view);
            }

            /**
             * @Get("/operator/{id}", name="admin_rest_order_operator", options={"expose"=true})
             */
            public function getOperatorAction($id)
            {
                $order = $this->getDoctrine()->getManager()->getRepository('Celsius3CoreBundle:Order')->find($id);
                if (!$order) {
                    throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.not_found_entity.order');
                }
                $instance = $this->getInstance();
                $admins = $this->getDoctrine()->getManager()->getRepository('Celsius3CoreBundle:BaseUser')->findAdmins($instance);
                $interaction['result'] = true;
                $interaction['order'] = $id;
                foreach ($admins as $key => $value) {
                    $interaction['admins'][$key] = $value;
                }
                $view = $this->view($interaction, 200)->setFormat('json');

                return $this->handleView($view);
            }

            /**
             * @Get("/change-operator/{order_id}/{id}", name="admin_rest_order_change_operator", options={"expose"=true})
             */
            public function changeOperatorAction($order_id, $id)
            {
                $context = SerializationContext::create()->setGroups(array('administration_order_show'));

                $instance = $this->getInstance();

                $operator = $this->getDoctrine()->getManager()->getRepository('Celsius3CoreBundle:BaseUser')->find($id);
                $order = $this->getDoctrine()->getManager()->getRepository('Celsius3CoreBundle:Order')->find($order_id);
                if (!$order) {
                    throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.entity_not_found.order');
                }

                $request = $this->getDoctrine()->getManager()->getRepository('Celsius3CoreBundle:Request')->findOneBy(array('order' => $order, 'instance' => $instance));

                $request->setOperator($operator);
                $em = $this->getDoctrine()->getManager();
                $em->persist($request);
                $em->flush();

                $view = $this->view($request, 200)->setFormat('json');
                $view->setSerializationContext($context);

                return $this->handleView($view);
            }
}
