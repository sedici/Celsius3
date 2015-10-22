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
use FOS\RestBundle\Controller\Annotations\Post;
use JMS\Serializer\SerializationContext;
use Celsius3\CoreBundle\Manager\StateManager;

/**
 * User controller.
 *
 * @Route("/admin/rest/users")
 */
class AdminBaseUserRestController extends BaseInstanceDependentRestController
{

    /**
     * GET Route annotation.
     * @Get("", name="admin_rest_user", options={"expose"=true})
     */
    public function getUsersAction()
    {
        $users = $this->getDoctrine()->getManager()
                ->getRepository('Celsius3CoreBundle:BaseUser')
                ->findBy(array(
            'instance' => $this->getInstance(),
        ));

        $view = $this->view(array_values($users), 200)->setFormat('json');

        return $this->handleView($view);
    }

    /**
     * GET Route annotation.
     * @Get("/pending", name="admin_rest_user_pending", options={"expose"=true})
     */
    public function getPendingUsersAction()
    {
        $context = SerializationContext::create()->setGroups(array('administration'));

        $users = $this->getDoctrine()->getManager()
                ->getRepository('Celsius3CoreBundle:BaseUser')
                ->findPendingUsers($this->getInstance());

        $view = $this->view(array_values($users), 200)->setFormat('json');
        $view->setSerializationContext($context);

        return $this->handleView($view);
    }

    /**
     * @Post("/enable", name="admin_rest_user_enable", options={"expose"=true})
     */
    public function enableUserAction(Request $request)
    {
        $user_id = $request->request->get('id', null);

        $user = $this->getDoctrine()->getManager()
                ->getRepository('Celsius3CoreBundle:BaseUser')
                ->findOneBy(array(
            'instance' => $this->getInstance()->getId(),
            'id' => $user_id,
        ));

        if (!$user) {
            return $this->createNotFoundException('User not found.');
        }

        $user->setEnabled(true);
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        $view = $this->view($user->isEnabled(), 200)->setFormat('json');

        return $this->handleView($view);
    }

    /**
     * @Post("/reject", name="admin_rest_user_reject", options={"expose"=true})
     */
    public function rejectUserAction(Request $request)
    {
        $user_id = $request->request->get('id', null);

        $user = $this->getDoctrine()->getManager()
                ->getRepository('Celsius3CoreBundle:BaseUser')
                ->findOneBy(array(
            'instance' => $this->getInstance()->getId(),
            'id' => $user_id,
        ));

        if (!$user) {
            return $this->createNotFoundException('User not found.');
        }

        if (!$user->isEnabled()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();
        }

        $view = $this->view(!$user->isEnabled(), 200)->setFormat('json');

        return $this->handleView($view);
    }

    /**
     * GET Route annotation.
     * @Get("/{id}", name="admin_rest_user_get", options={"expose"=true})
     */
    public function getUserAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('Celsius3CoreBundle:BaseUser')->find($id);

        if (!$user) {
            return $this->createNotFoundException('User not found.');
        }

        $view = $this->view($user, 200)->setFormat('json');

        return $this->handleView($view);
    }

    /**
     * GET Route annotation.
     * @Get("/{id}/orders/{type}", name="admin_rest_user_get_orders", options={"expose"=true})
     */
    public function getOrders($id, $type)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('Celsius3CoreBundle:BaseUser')->find($id);
        $currentPage = $this->get('request')->query->get('currentPage', 1);
        $resultsPerPage = $this->get('request')->query->get('resultsPerPage', 10);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find BaseUser.');
        }

        if ($type === 'active') {
            $ordersQuery = $em->getRepository('Celsius3CoreBundle:Order')
                    ->findForInstance($this->getInstance(), null, array(StateManager::STATE__CREATED, StateManager::STATE__SEARCHED, StateManager::STATE__REQUESTED, StateManager::STATE__APPROVAL_PENDING), $entity);
        }
        if ($type === 'ready') {
            $ordersQuery = $em->getRepository('Celsius3CoreBundle:Order')
                    ->findForInstance($this->getInstance(), null, StateManager::STATE__RECEIVED, $entity);
        }
        if ($type === 'history') {
            $ordersQuery = $em->getRepository('Celsius3CoreBundle:Order')
                    ->findForInstance($this->getInstance(), null, array(StateManager::STATE__DELIVERED, StateManager::STATE__ANNULLED, StateManager::STATE__CANCELLED), $entity);
        }

        $totalQuery = clone($ordersQuery);
        $total = $totalQuery->select('count(r)')->getQuery()->getSingleScalarResult();

        $ordersQuery
                ->setMaxResults(intval($resultsPerPage))
                ->setFirstResult((intval($currentPage) - 1) * 10);

        $orders = $ordersQuery->getQuery()->execute();

        $view = $this->view(array('orders' => $orders, 'total' => $total), 200)->setFormat('json');

        return $this->handleView($view);
    }

}
