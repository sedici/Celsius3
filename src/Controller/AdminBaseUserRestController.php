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

use Celsius3\Controller\Base\EmailController;
use Celsius3\Controller\Base\EmailTemplateController;
use Celsius3\Controller\Base\UserController;
use Celsius3\Entity\Order;
use Celsius3\Manager\StateManager;
use Celsius3\Entity\BaseUser;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


#[
    Route('/rest/v1/admin/users'),
    IsGranted('ROLE_ADMIN')
]
class AdminBaseUserRestController extends UserController
{

    public function initialize(): void
    {
        parent::initialize();
        $this->setInstanceDependent(true);
    }

    #[Route(
        '/',
        name: 'admin_rest_user',
        options: ['expose' => true]
    )]
    public function getUsers(): Response
    { return $this->restRenderer->render($this->listQuery(), serializerGroups: 'api'); }


    #[Route(
        '/pending',
        name: 'admin_rest_user_pending',
        options: ['expose' => true]
    )]
    public function getPendingUsers(): Response
    {
        return $this->restRenderer->render(
            $this->repository->findPendingUsers($this->instance),
            serializerGroups: 'administration'
        );
    }


    #[Route(
        '/enable',
        name: 'admin_rest_user_enable',
        methods: ['POST'],
        options: ['expose' => true]
    )]
    public function restEnableUser(
        EmailController $emailController,
        EmailTemplateController $emailTemplateController
    ): Response {
        $json_content = $this->requestStack->getCurrentRequest()->toArray();
        $user_id = $json_content["id"];

        $user = $this->repository->find($user_id);
        if (!$user) $this->error('entity_not_found');

        $user->setEnabled(true)
            ->setPdf(true)
            ->setDownloadAuth(true);
        
        $this->persistEntity($user);

        if ($user->isEnabled()) {
            // $text = $emailController
            //     ->renderTemplate(
            //         'user_welcome', $this->instance->getId()
            //     );

            // $emailController->sendEmail(
            //     $user->getEmail(),
            //     $emailTemplateController->getTemplate(
            //         'user_welcome', $this->instance
            //     )->getTitle(),
            //     $text
            // );
        }

        return $this->restRenderer->render($user->isEnabled(), serializerGroups: 'api');

        // $user = $this->entityManager
        //     ->getRepository(BaseUser::class)
        //     ->findOneBy(
        //         [
        //             'instance' => $this->getInstance()->getId(),
        //             'id' => $user_id,
        //         ]
        //     );

        // if (!$user) {
        //     throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.entity_not_found.user');
        // }

        // $user->setEnabled(true)
        //     ->setPdf(true)
        //     ->setDownloadAuth(true);

        // $em = $this->entityManager;
        // $em->persist($user);
        // $em->flush();

        // if ($user->isEnabled()) {
        //     $mailManager = $this->get('celsius3_core.mail_manager');
        //     $text = $mailManager->renderTemplate('user_welcome', $this->getInstance(), $user);

        //     $this->get('celsius3_core.mailer')->sendEmail(
        //         $user->getEmail(),
        //         $mailManager->getTemplate(
        //             'user_welcome',
        //             $this->getInstance()
        //         )->getTitle(),
        //         $text,
        //         $this->getInstance()
        //     );
        // }

        // $view = $this->view($user->isEnabled(), 200)->setFormat('json');

        // return $this->viewHandler->handle($view);
    }


    #[Route(
        '/reject',
        name: 'admin_rest_user_reject',
        methods: ['POST'],
        options: ['expose' => true]
    )]
    public function rejectUser()
    {
        $request = $this->requestStack->getCurrentRequest();
        $user_id = $request->get('id', null);

        $user = $this->repository
            ->findOneBy(
                [
                    'instance' => $this->instance->getId(),
                    'id' => $user_id,
                ]
            );

        if (!$user) $this->error('entity_not_found');

        try {
            if (!$user->isEnabled()) $this->persistEntity($user);
        } catch (\Exception $e) {
            $this->error('can_not_delete');
        }

        return $this->restRenderer->render($user, serializerGroups: 'api');
    }


    #[Route(
        '/get_admins',
        name: 'admin_rest_get_other_admins',
        options: ['expose' => true]
    )]
    public function getOtherAdmins(): Response
    {
        $admins = $this->repository->findAdmins($this->instance);

        $filteredAdmins = array_filter(
            $admins,
            function (BaseUser $admin): bool {
                return (int)$admin->getId() !== (int)$this->getUser()->getId();
            }
        );

        return $this->restRenderer->render($filteredAdmins, serializerGroups: 'admins-select');
    }


    #[Route(
        '/{id}/show',
        name: 'admin_rest_user_get',
        options: ['expose' => true]
    )]
    public function restShow(string $id): Response
    { return $this->restRenderer->show($id, 'api'); }


    #[Route(
        '/{id}/orders/{type}',
        name: 'admin_rest_user_get_orders',
        options: ['expose' => true]
    )]
    public function getOrders(string $id, string $type): Response
    {
        $entity = $this->repository->find($id);
        if (!$entity) $this->error('entity_not_found');

        if ($type === 'active') 
            $state = [
                StateManager::STATE__CREATED,
                StateManager::STATE__SEARCHED,
                StateManager::STATE__REQUESTED,
                StateManager::STATE__APPROVAL_PENDING
            ];
        elseif ($type === 'ready')
            $state = StateManager::STATE__RECEIVED;
        elseif ($type === 'history')
            $state = [
                StateManager::STATE__DELIVERED,
                StateManager::STATE__ANNULLED,
                StateManager::STATE__CANCELLED
            ];

        $ordersQuery = $this->entityManager
            ->getRepository(Order::class)
            ->findForInstance(
                $this->instance, null, $state, $entity
            );

        $totalQuery = clone $ordersQuery;
        $total = $totalQuery->select('count(DISTINCT o)')
            ->getQuery()->getSingleScalarResult();

        $orders = $this->paginate($ordersQuery, options: [
            'defaultSortFieldName' => 'o.updatedAt',
            'defaultSortDirection' => 'asc',
        ]);

        return $this->restRenderer->render(
            ['orders' => $orders, 'total' => $total],
            serializerGroups: 'administration_user_show'
        );
    }
}
