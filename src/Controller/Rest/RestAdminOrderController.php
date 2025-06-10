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

namespace Celsius3\Controller\Rest;

use Celsius3\Controller\Base\OrderController;
use Celsius3\Entity\BaseUser;
use Celsius3\Entity\Institution;
use Celsius3\Entity\Order;
use Celsius3\Entity\State;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations\Get;
use Celsius3\Exception\Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;


#[
    Route("/rest/v1/admin/orders"),
]
class RestAdminOrderController extends OrderController
{

    #[Route(
        "/",
        name: "admin_rest_order",
        options: ["expose" => true],
        methods: ['GET']
    )]
    public function getOrders(): Response
    {
        $request = $this->requestStack->getCurrentRequest();
        if ($request->query->get('type', null) === 'mine') {
            $user = $this->getUser();
        } else {
            $user = null;
        }

        $state = $request->query->get('state', null);

        $orders = $this->repository
            ->findForInstance(
                $this->instance, $user, $state
            );


        return $this->restRenderer->render($orders);
    }


    #[Route(
        "/count",
        name: "admin_rest_order_count_get",
        options: ["expose" => true],
        methods: ['GET']
    )]
    public function getOrderCount(): Response
    {
        $request = $this->requestStack->getCurrentRequest();

        if ($request->query->get('type', null) === 'mine') {
            $user = $this->security->getUser();
        } elseif (is_int(intval($request->query->get('type', null)))) {
            $user = $this->entityManager->getRepository(BaseUser::class)
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

        $orderCount = $this->entityManager
            ->getRepository(State::class)
            ->countOrders($this->instance, $user, $orderType);

        return $this->restRenderer->render($orderCount);
    }


    #[Route(
        "/get",
        name: "admin_rest_order_request_get",
        options: ["expose" => true],
        methods: ['GET']
    )]
    public function getOrdersAndRequests(): Response
    {
        $em = $this->entityManager;
        $request = $this->requestStack->getCurrentRequest();

        if ($request->query->get('type', null) === 'mine') {
            $user = $this->security->getUser();
        } elseif (is_int(intval($request->query->get('type', null)))) {
            $user = $em->getRepository(BaseUser::class)
                ->find(intval($request->query->get('type', null)));
            if (!is_null($user) && !$user->hasRole('ROLE_ADMIN') && !$user->hasRole('ROLE_SUPER_ADMIN')) {
                $user = null;
            }
        } else {
            $user = null;
        }

        $state = $request->query->get('state', null);
        $orderType = $request->query->get('orderType', null);

        $states = $em->getRepository(Order::class)
            ->findForInstance($this->instanceHelper->getSessionInstance(), $user, $state, null, $orderType);

        $paginator = $this->paginator;
        $pagination = $paginator->paginate(
            $states,
            $request->query->get('page', 1)/* page number */,
            $this->getResultsPerPage()/* limit per page */,
            ['sort' => 'createdAt']
        )->getItems();

        $requests = $em->getRepository(\Celsius3\Entity\Request::class)
            ->findRequestForOrders($pagination);

        $response = array(
            'orders' => array_values($pagination),
            'requests' => array_column(
                array_map(
                    function (\Celsius3\Entity\Request $request) {
                        return [
                            'id' => $request->getOrder()->getId(),
                            'request' => $request,
                        ];
                    },
                    $requests
                ),
                'request',
                'id'
            ),
        );

        return $this->restRenderer->render($response, serializerGroups: 'administration_list');
    }


    #[Route(
        "/{id}",
        name: "admin_rest_order_get",
        options: ["expose" => true],
        methods: ['GET']
    )]
    public function getOrder(string $id): Response
    {
        return $this->restRenderer->show(
            $id, serializerGroups: 'administration_order_show'
        );
    }


    #[Route(
        "/interaction/{id}",
        name: "admin_rest_order_interaction",
        options: ["expose" => true],
        methods: ['GET']
    )]
    public function getInteraction(string $id)
    {
        $order = $this->entityManager->getRepository(Order::class)->find($id);
        $institution = $order->getOriginalRequest()->getOwner()->getInstitution();

        $instance = $this->instanceHelper->getSessionInstance();
        $interaction['result'] = false;

        if ($institution->getInstance() !== $instance) {
            $institutionRepository = $this->entityManager->getRepository(Institution::class);
            $baseInstitution = $institutionRepository->getBaseInstitution($institution);

            $interaction['result'] = true;
            $institutions = $this->entityManager->getRepository(Institution::class)->getInstitutionsTree(
                $baseInstitution
            );

            $requestRepository = $this->entityManager->getRepository(\Celsius3\Entity\Request::class);
            $response['institutionInteraction'] = $requestRepository->getInteractionOfInstitutionWithInstance(
                $instance,
                $institutions
            );
            $response['instanceInteraction'] = $requestRepository->getInteractionOfInstanceWithInstitution(
                $instance,
                $institutions
            );

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

        $this->restRenderer->render($interaction);
    }


    #[Route(
        "/operator/{id}",
        name: "admin_rest_order_operator",
        options: ["expose" => true],
        methods: ['GET']
    )]
    public function getOperator(string $id): Response
    {
        $order = $this->repository->find($id);
        if (!$order) $this->error(Exception::ENTITY_NOT_FOUND);

        // $instance = $this->instanceHelper->getSessionInstance();
        $admins = $this->entityManager
            ->getRepository(BaseUser::class)
            ->findAdmins($this->instance);

        $interaction = [
            'result' => false,
            'order' => $id,
            'admins' => [],
        ];
        foreach ($admins as $key => $value) {
            $interaction['admins'][$key] = $value;
        }

        // throw new \Exception((string)var_dump($interaction));
        return $this->restRenderer->render(
            $interaction, serializerGroups: 'administration_order_show'
        );
    }


    #[Route(
        "/change-operator/{order_id}/{id}",
        name: "admin_rest_order_change_operator",
        options: ["expose" => true],
        methods: ['GET']
    )]
    public function changeOperator(string $order_id, string $id): mixed
    {
        $instance = $this->instanceHelper->getSessionInstance();

        $operator = $this->entityManager->getRepository(BaseUser::class)->find($id);
        $order = $this->entityManager->getRepository(Order::class)->find($order_id);
        if (!$order) {
            throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.entity_not_found.order');
        }

        $request = $this->entityManager->getRepository(\Celsius3\Entity\Request::class)->findOneBy(
            array('order' => $order, 'instance' => $instance)
        );

        $request->setOperator($operator);
        $em = $this->entityManager;
        $em->persist($request);
        $em->flush();

        return $this->restRenderer->render($request, serializerGroups: 'administration_order_show');
    }
}
