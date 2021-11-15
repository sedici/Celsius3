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

namespace Celsius3\Controller;

use Celsius3\Entity\Instance;
use Celsius3\Entity\Order;
use Celsius3\Helper\InstanceHelper;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\ViewHandlerInterface;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use JMS\Serializer\SerializationContext;
use Celsius3\Manager\StateManager;
use Celsius3\Exception\Exception;
use Celsius3\Entity\BaseUser;
use Symfony\Component\Security\Core\Security;

/**
 * User controller.
 *
 * @Route("/admin/rest/users")
 */
class AdminBaseUserRestController extends FOSRestController//BaseInstanceDependentRestController
{
    private $viewHandler;
    private $entityManager;
    private $instanceHelper;
    private $security;

    public function __construct(
        ViewHandlerInterface $viewHandler,
        EntityManagerInterface $entityManager,
        InstanceHelper $instanceHelper,
        Security $security
    )
    {
        $this->viewHandler = $viewHandler;
        $this->entityManager = $entityManager;
        $this->instanceHelper = $instanceHelper;
        $this->security = $security;
    }

    protected function getInstance(): Instance
    {
        return $this->instanceHelper->getSessionInstance();
    }

    /**
     * GET Route annotation.
     *
     * @Get("", name="admin_rest_user", options={"expose"=true})
     */
    public function getUsers()
    {
        $users = $this->entityManager
            ->getRepository(BaseUser::class)
            ->findBy(
                [
                    'instance' => $this->getInstance(),
                ]
            );

        $view = $this->view(array_values($users), 200)->setFormat('json');

        return $this->viewHandler->handle($view);
    }

    /**
     * GET Route annotation.
     *
     * @Get("/pending", name="admin_rest_user_pending", options={"expose"=true})
     */
    public function getPendingUsers()
    {
        $context = SerializationContext::create()->setGroups(['administration']);

        $users = $this->entityManager
            ->getRepository(BaseUser::class)
            ->findPendingUsers($this->getInstance());

        $view = $this->view(array_values($users), 200)->setFormat('json');
        $view->setSerializationContext($context);

        return $this->viewHandler->handle($view);
    }

    /**
     * @Post("/enable", name="admin_rest_user_enable", options={"expose"=true})
     */
    public function enableUser(Request $request)
    {
        $user_id = $request->request->get("id", null);

        $user = $this->entityManager
            ->getRepository(BaseUser::class)
            ->findOneBy(
                [
                    'instance' => $this->getInstance()->getId(),
                    'id' => $user_id,
                ]
            );

        if (!$user) {
            throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.entity_not_found.user');
        }

        $user->setEnabled(true)
            ->setPdf(true)
            ->setDownloadAuth(true);

        $em = $this->entityManager;
        $em->persist($user);
        $em->flush();

        if ($user->isEnabled()) {
            $mailManager = $this->get('celsius3_core.mail_manager');
            $text = $mailManager->renderTemplate('user_welcome', $this->getInstance(), $user);

            $this->get('celsius3_core.mailer')->sendEmail(
                $user->getEmail(),
                $mailManager->getTemplate(
                    'user_welcome',
                    $this->getInstance()
                )->getTitle(),
                $text,
                $this->getInstance()
            );
        }

        $view = $this->view($user->isEnabled(), 200)->setFormat('json');

        return $this->viewHandler->handle($view);
    }

    /**
     * @Post("/reject", name="admin_rest_user_reject", options={"expose"=true})
     */
    public function rejectUser(Request $request)
    {
        $user_id = $request->request->get('id', null);

        $user = $this->entityManager
            ->getRepository(BaseUser::class)
            ->findOneBy(
                [
                    'instance' => $this->getInstance()->getId(),
                    'id' => $user_id,
                ]
            );

        if (!$user) {
            throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.entity_not_found.user');
        }

        try {
            if (!$user->isEnabled()) {
                $em = $this->entityManager;
                $em->remove($user);
                $em->flush();
            }
        } catch (\Exception $e) {
            throw Exception::create(Exception::CAN_NOT_DELETE, 'exception.can_not_delete.user');
        }

        $view = $this->view(!$user->isEnabled(), 200)->setFormat('json');

        return $this->viewHandler->handle($view);
    }

    /**
     * @Get("/get_admins", name="admin_rest_get_other_admins", options={"expose"=true})
     */
    public function getOtherAdmins()
    {
        $repository = $this->entityManager->getRepository(BaseUser::class);

        $admins = $repository->findAdmins($this->getInstance());

        $filteredAdmins = array_filter(
            $admins,
            function (BaseUser $admin) {
                return (int)$admin->getId() !== (int)$this->security->getUser()->getId();
            }
        );

        $context = SerializationContext::create()->setGroups(['admins-select']);

        $view = $this->view(array_values($filteredAdmins), 200)->setFormat('json');
        $view->setSerializationContext($context);

        return $this->viewHandler->handle($view);
    }

    /**
     * GET Route annotation.
     *
     * @Get("/{id}", name="admin_rest_user_get", options={"expose"=true})
     */
    public function user($id)
    {
        $em = $this->entityManager;

        $user = $em->getRepository(BaseUser::class)->find($id);

        if (!$user) {
            throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.entity_not_found.user');
        }

        $view = $this->view($user, 200)->setFormat('json');

        return $this->viewHandler->handle($view);
    }

    /**
     * GET Route annotation.
     *
     * @Get("/{id}/orders/{type}", name="admin_rest_user_get_orders", options={"expose"=true})
     */
    public function getOrders($id, $type)
    {
        $context = SerializationContext::create()->setGroups(['administration_user_show']);

        $em = $this->entityManager;

        $entity = $em->getRepository(BaseUser::class)->find($id);

        if (!$entity) {
            throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.entity_not_found.user');
        }

        if ($type === 'active') {
            $ordersQuery = $em->getRepository(Order::class)
                ->findForInstance(
                    $this->getInstance(),
                    null,
                    [
                        StateManager::STATE__CREATED,
                        StateManager::STATE__SEARCHED,
                        StateManager::STATE__REQUESTED,
                        StateManager::STATE__APPROVAL_PENDING
                    ],
                    $entity
                );
        } elseif ($type === 'ready') {
            $ordersQuery = $em->getRepository(Order::class)
                ->findForInstance($this->getInstance(), null, StateManager::STATE__RECEIVED, $entity);
        } elseif ($type === 'history') {
            $ordersQuery = $em->getRepository(Order::class)
                ->findForInstance(
                    $this->getInstance(),
                    null,
                    [
                        StateManager::STATE__DELIVERED,
                        StateManager::STATE__ANNULLED,
                        StateManager::STATE__CANCELLED
                    ],
                    $entity
                );
        }

        $totalQuery = clone $ordersQuery;
        $total = $totalQuery->select('count(DISTINCT o)')->getQuery()->getSingleScalarResult();

        $paginator = $this->get('knp_paginator');
        $orders = $paginator->paginate(
            $ordersQuery,
            $this->get('request_stack')->getCurrentRequest()->query->get('page', 1)
            /* page number */,
            $this->getResultsPerPage()/* limit per page */,
            $this->getSortDefaults()
        )->getItems();

        $view = $this->view(['orders' => $orders, 'total' => $total], 200)->setFormat('json');
        $view->setSerializationContext($context);

        return $this->viewHandler->handle($view);
    }

    protected function getSortDefaults()
    {
        return [
            'defaultSortFieldName' => 'o.updatedAt',
            'defaultSortDirection' => 'asc',
        ];
    }
}
